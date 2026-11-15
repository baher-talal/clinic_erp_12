<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorManageController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')
            ->withCount(['appointments as today_count' => fn($q) => $q->whereDate('appointment_date', today())])
            ->withCount('appointments')
            ->paginate(20);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user');
        $appointments = $doctor->appointments()
            ->with('patient')
            ->latest('appointment_date')
            ->paginate(10);
        $totalRevenue = $doctor->appointments()
            ->where('payment_status', 'paid')
            ->sum('fee');
        return view('admin.doctors.show', compact('doctor', 'appointments', 'totalRevenue'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'specialization'   => 'required|string|max:100',
            'consultation_fee' => 'required|numeric|min:0',
            'bio'              => 'nullable|string',
            'is_available'     => 'boolean',
        ]);

        $doctor->update($request->only(['specialization', 'consultation_fee', 'bio', 'is_available']));

        return back()->with('success', 'تم تحديث بيانات الطبيب');
    }
}
