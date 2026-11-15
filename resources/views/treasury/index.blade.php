@extends('layouts.app')
@section('title', 'الخزنة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الخزنة</li>
@endsection

@section('content')
{{-- ملخص الخزنة --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10">
                    <i class="fas fa-arrow-down text-success"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($totalIncome, 2) }}</div>
                    <div class="text-muted small">إجمالي الإيرادات (ج.م)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10">
                    <i class="fas fa-arrow-up text-danger"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($totalExpense, 2) }}</div>
                    <div class="text-muted small">إجمالي المصروفات (ج.م)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon {{ $balance >= 0 ? 'bg-primary' : 'bg-danger' }} bg-opacity-10">
                    <i class="fas fa-wallet {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">
                        {{ number_format($balance, 2) }}
                    </div>
                    <div class="text-muted small">الرصيد الحالي (ج.م)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- جدول المعاملات --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>سجل المعاملات</h6>
                <a href="{{ route('treasury.report') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-chart-bar me-1"></i> تقرير
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الكود</th>
                                <th>النوع</th>
                                <th>الفئة</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td><small class="text-muted">{{ $trx->transaction_code }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $trx->type === 'income' ? 'success' : 'danger' }}">
                                        {{ $trx->type_label }}
                                    </span>
                                </td>
                                <td>{{ $trx->category_label }}</td>
                                <td class="fw-semibold {{ $trx->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->type === 'income' ? '+' : '-' }} {{ number_format($trx->amount, 2) }}
                                </td>
                                <td>
                                    @php $methods = ['cash'=>'كاش','card'=>'بطاقة','transfer'=>'تحويل'] @endphp
                                    {{ $methods[$trx->payment_method] ?? $trx->payment_method }}
                                </td>
                                <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">لا توجد معاملات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $transactions->links() }}</div>
            </div>
        </div>
    </div>

    {{-- إضافة معاملة يدوية --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-success"></i>إضافة معاملة يدوية</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('treasury.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">النوع</label>
                        <select name="type" class="form-select" required>
                            <option value="income">إيراد</option>
                            <option value="expense">مصروف</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">الفئة</label>
                        <select name="category" class="form-select" required>
                            <option value="appointment_fee">رسوم كشف</option>
                            <option value="medicine_sale">مبيعات صيدلية</option>
                            <option value="salary">رواتب</option>
                            <option value="rent">إيجار</option>
                            <option value="utilities">مرافق</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">المبلغ (ج.م)</label>
                        <input type="number" name="amount" class="form-control" min="0.01" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">طريقة الدفع</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">كاش</option>
                            <option value="card">بطاقة</option>
                            <option value="transfer">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">التاريخ</label>
                        <input type="date" name="transaction_date" class="form-control"
                            value="{{ today()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ملاحظات</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save me-1"></i> حفظ المعاملة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
