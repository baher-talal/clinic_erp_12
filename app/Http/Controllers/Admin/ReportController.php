<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Appointment, Patient, Doctor, TreasuryTransaction, Medicine, Call, Followup};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function appointments(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $stats = [
            'total'     => Appointment::whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])->count(),
            'completed' => Appointment::whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])->where('status', 'cancelled')->count(),
            'revenue'   => Appointment::whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])->where('payment_status', 'paid')->sum('fee'),
        ];

        $byDoctor = Doctor::with('user')
            ->withCount(['appointments as count' => fn($q) => $q->whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])])
            ->get()->sortByDesc('count');

        $daily = Appointment::whereBetween(DB::raw('DATE(appointment_date)'), [$from, $to])
            ->select(DB::raw('DATE(appointment_date) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(fee) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.appointments', compact('stats', 'byDoctor', 'daily', 'from', 'to'));
    }

    public function patients(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $newPatients   = Patient::whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->count();
        $totalPatients = Patient::count();
        $byGender      = Patient::select('gender', DB::raw('COUNT(*) as count'))->groupBy('gender')->pluck('count', 'gender');
        $byBloodType   = Patient::whereNotNull('blood_type')->select('blood_type', DB::raw('COUNT(*) as count'))->groupBy('blood_type')->pluck('count', 'blood_type');

        return view('admin.reports.patients', compact('newPatients', 'totalPatients', 'byGender', 'byBloodType', 'from', 'to'));
    }

    public function financial(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $income  = TreasuryTransaction::where('type', 'income')->whereBetween('transaction_date', [$from, $to])->sum('amount');
        $expense = TreasuryTransaction::where('type', 'expense')->whereBetween('transaction_date', [$from, $to])->sum('amount');

        $byCategory = TreasuryTransaction::whereBetween('transaction_date', [$from, $to])
            ->select('type', 'category', DB::raw('SUM(amount) as total'))
            ->groupBy('type', 'category')
            ->get();

        $monthly = TreasuryTransaction::select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month', 'type')
            ->orderBy('year')->orderBy('month')
            ->get();

        return view('admin.reports.financial', compact('income', 'expense', 'byCategory', 'monthly', 'from', 'to'));
    }
}
