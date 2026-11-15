<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    private function getDoctor()
    {
        return auth()->user()->doctor;
    }

    public function index()
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'لم يتم ربط حسابك بملف طبيب');
        }

        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        $pendingCount   = $todayAppointments->whereIn('status', ['pending','confirmed'])->count();
        $completedToday = $todayAppointments->where('status', 'completed')->count();
        $totalPatients  = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')->count('patient_id');

        $upcomingAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', now())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('doctor.dashboard.index', compact(
            'doctor', 'todayAppointments', 'pendingCount',
            'completedToday', 'totalPatients', 'upcomingAppointments'
        ));
    }

    public function appointments(Request $request)
    {
        $doctor = $this->getDoctor();

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('appointment_date', 'desc')
            ->paginate(20);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function patients(Request $request)
    {
        $doctor = $this->getDoctor();

        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()->pluck('patient_id');

        $patients = Patient::whereIn('id', $patientIds)
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%"))
            ->paginate(20);

        return view('doctor.patients.index', compact('patients'));
    }

    public function patientProfile(Patient $patient)
    {
        $doctor = $this->getDoctor();

        // الطبيب يرى فقط مرضاه
        $hasRelation = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)->exists();

        if (!$hasRelation && !auth()->user()->isAdmin()) {
            abort(403, 'هذا المريض ليس ضمن مرضاك');
        }

        $patient->load(['appointments' => fn($q) => $q->where('doctor_id', $doctor->id)->with('medicalRecord')]);
        $medicalRecords = $patient->medicalRecords()
            ->where('doctor_id', $doctor->id)
            ->with('prescription.items.medicine')
            ->latest()
            ->get();

        return view('doctor.patients.show', compact('patient', 'medicalRecords'));
    }
}
