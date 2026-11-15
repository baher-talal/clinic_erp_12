<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::withCount('appointments')->latest()->paginate(20);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'national_id'     => 'nullable|unique:patients,national_id|max:14',
            'gender'          => 'required|in:male,female',
            'birth_date'      => 'required|date|before:today',
            'phone'           => 'required|string|max:20',
            'phone_emergency' => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'blood_type'      => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies'       => 'nullable|string',
            'chronic_diseases'=> 'nullable|string',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'تم إضافة المريض بنجاح - كود المريض: ' . $patient->patient_code);
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor.user', 'medicalRecords', 'prescriptions']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'national_id'     => 'nullable|unique:patients,national_id,' . $patient->id . '|max:14',
            'gender'          => 'required|in:male,female',
            'birth_date'      => 'required|date|before:today',
            'phone'           => 'required|string|max:20',
            'phone_emergency' => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'blood_type'      => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies'       => 'nullable|string',
            'chronic_diseases'=> 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'تم تحديث بيانات المريض بنجاح');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
            ->with('success', 'تم حذف المريض بنجاح');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $patients = Patient::where('name', 'like', "%{$query}%")
            ->orWhere('patient_code', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('national_id', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'patient_code', 'name', 'phone']);

        return response()->json($patients);
    }
}
