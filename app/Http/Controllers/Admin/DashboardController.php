<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\TreasuryTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات اليوم
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $todayIncome = TreasuryTransaction::where('type', 'income')
            ->whereDate('transaction_date', today())
            ->sum('amount');

        // إحصائيات عامة
        $totalPatients   = Patient::count();
        $totalDoctors    = Doctor::where('is_available', true)->count();
        $pendingPayments = Appointment::where('payment_status', 'unpaid')
            ->where('status', '!=', 'cancelled')
            ->count();

        // تنبيهات المخزن
        $lowStockMedicines = Medicine::where('is_active', true)
            ->whereRaw('quantity_in_stock <= min_stock_alert')
            ->count();

        // آخر المواعيد
        $recentAppointments = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->limit(10)
            ->get();

        // إيرادات الأسبوع
        $weeklyIncome = TreasuryTransaction::where('type', 'income')
            ->where('transaction_date', '>=', now()->startOfWeek())
            ->select(DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        return view('dashboard.index', compact(
            'todayAppointments', 'todayIncome', 'totalPatients', 'totalDoctors',
            'pendingPayments', 'lowStockMedicines', 'recentAppointments', 'weeklyIncome'
        ));
    }
}
