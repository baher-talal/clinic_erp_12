<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\TreasuryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->latest()
            ->paginate(20);

        $doctors = Doctor::with('user')->where('is_available', true)->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $doctors  = Doctor::with('user')->where('is_available', true)->get();
        $patients = Patient::latest()->get(['id', 'patient_code', 'name', 'phone']);
        return view('appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'type'             => 'required|in:new,follow_up,emergency',
            'fee'              => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status']     = 'pending';
        $validated['payment_status'] = 'unpaid';

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'تم حجز الموعد بنجاح - ' . $appointment->appointment_code);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'medicalRecord', 'createdBy']);
        return view('appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الموعد');
    }

    public function markPaid(Request $request, Appointment $appointment)
    {
        DB::transaction(function () use ($appointment, $request) {
            $appointment->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
            ]);

            TreasuryTransaction::create([
                'transaction_code' => 'TRX-' . now()->format('YmdHis') . '-' . $appointment->id,
                'created_by'       => auth()->id(),
                'type'             => 'income',
                'category'         => 'appointment_fee',
                'amount'           => $appointment->fee,
                'payment_method'   => $request->payment_method ?? 'cash',
                'description'      => 'رسوم كشف - ' . $appointment->appointment_code,
                'related_id'       => $appointment->id,
                'related_type'     => Appointment::class,
                'transaction_date' => today(),
            ]);
        });

        return back()->with('success', 'تم تسجيل الدفع بنجاح');
    }

    public function calendar()
    {
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->map(fn($a) => [
                'id'    => $a->id,
                'title' => $a->patient->name . ' - د. ' . $a->doctor->name,
                'start' => $a->appointment_date->toIso8601String(),
                'color' => match($a->status) {
                    'pending'     => '#ffc107',
                    'confirmed'   => '#17a2b8',
                    'in_progress' => '#007bff',
                    'completed'   => '#28a745',
                    default       => '#6c757d',
                },
                'url'   => route('appointments.show', $a->id),
            ]);

        return view('appointments.calendar', compact('appointments'));
    }
}
