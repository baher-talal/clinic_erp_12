<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function create(Appointment $appointment)
    {
        $appointment->load('patient', 'doctor.user');
        $medicines = Medicine::where('is_active', true)->get(['id', 'name', 'unit']);
        $previousRecords = MedicalRecord::where('patient_id', $appointment->patient_id)
            ->with('appointment')
            ->latest()
            ->limit(5)
            ->get();

        return view('doctors.record.create', compact('appointment', 'medicines', 'previousRecords'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'complaint'      => 'required|string',
            'diagnosis'      => 'required|string',
            'treatment_plan' => 'nullable|string',
            'notes'          => 'nullable|string',
            'weight'         => 'nullable|numeric',
            'height'         => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'temperature'    => 'nullable|numeric',
            'pulse'          => 'nullable|integer',
            // الروشتة
            'medicines'      => 'nullable|array',
            'medicines.*.medicine_id'   => 'required|exists:medicines,id',
            'medicines.*.dosage'        => 'required|string',
            'medicines.*.frequency'     => 'required|string',
            'medicines.*.duration_days' => 'required|integer|min:1',
            'medicines.*.notes'         => 'nullable|string',
            'prescription_instructions' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $appointment) {
            // إنشاء السجل الطبي
            $record = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => $appointment->patient_id,
                'doctor_id'      => $appointment->doctor_id,
                'complaint'      => $validated['complaint'],
                'diagnosis'      => $validated['diagnosis'],
                'treatment_plan' => $validated['treatment_plan'] ?? null,
                'notes'          => $validated['notes'] ?? null,
                'weight'         => $validated['weight'] ?? null,
                'height'         => $validated['height'] ?? null,
                'blood_pressure' => $validated['blood_pressure'] ?? null,
                'temperature'    => $validated['temperature'] ?? null,
                'pulse'          => $validated['pulse'] ?? null,
            ]);

            // إنشاء الروشتة إن وجدت
            if (!empty($validated['medicines'])) {
                $lastPrescription = Prescription::latest('id')->first();
                $nextId = $lastPrescription ? $lastPrescription->id + 1 : 1;

                $prescription = Prescription::create([
                    'medical_record_id'       => $record->id,
                    'patient_id'              => $appointment->patient_id,
                    'doctor_id'               => $appointment->doctor_id,
                    'prescription_code'       => 'RX-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT),
                    'instructions'            => $validated['prescription_instructions'] ?? null,
                    'status'                  => 'pending',
                ]);

                foreach ($validated['medicines'] as $med) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id'     => $med['medicine_id'],
                        'dosage'          => $med['dosage'],
                        'frequency'       => $med['frequency'],
                        'duration_days'   => $med['duration_days'],
                        'notes'           => $med['notes'] ?? null,
                    ]);
                }
            }

            // تحديث حالة الموعد إلى مكتمل
            $appointment->update(['status' => 'completed']);
        });

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'تم حفظ السجل الطبي وإنشاء الروشتة بنجاح');
    }

    public function show(MedicalRecord $record)
    {
        $record->load('patient', 'doctor.user', 'appointment', 'prescription.items.medicine');
        return view('doctors.record.show', compact('record'));
    }
}
