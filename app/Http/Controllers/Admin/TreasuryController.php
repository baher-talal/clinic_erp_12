<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TreasuryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreasuryController extends Controller
{
    public function index(Request $request)
    {
        $transactions = TreasuryTransaction::with('createdBy')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->from, fn($q) => $q->whereDate('transaction_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('transaction_date', '<=', $request->to))
            ->latest()
            ->paginate(25);

        $totalIncome  = TreasuryTransaction::where('type', 'income')->sum('amount');
        $totalExpense = TreasuryTransaction::where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        return view('treasury.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'           => 'required|in:income,expense',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,transfer',
            'description'    => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $validated['created_by']       = auth()->id();
        $validated['transaction_code'] = 'TRX-MAN-' . now()->format('YmdHis');

        TreasuryTransaction::create($validated);

        return back()->with('success', 'تم تسجيل المعاملة المالية بنجاح');
    }

    public function report(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $summary = TreasuryTransaction::whereBetween('transaction_date', [$from, $to])
            ->select('type', 'category', DB::raw('SUM(amount) as total'))
            ->groupBy('type', 'category')
            ->get();

        $dailyChart = TreasuryTransaction::whereBetween('transaction_date', [$from, $to])
            ->select('type', DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('type', 'date')
            ->orderBy('date')
            ->get();

        return view('treasury.report', compact('summary', 'dailyChart', 'from', 'to'));
    }
}
