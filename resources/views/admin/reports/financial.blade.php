@extends('layouts.app')
@section('title','التقارير المالية')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">التقارير المالية</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label small fw-semibold">من</label><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-4"><label class="form-label small fw-semibold">إلى</label><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100">عرض</button></div>
        </form>
    </div>
</div>

@php $net = $income - $expense; @endphp
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4"><div class="fs-1 fw-bold text-success">{{ number_format($income,0) }}</div><div class="text-muted">إجمالي الإيرادات</div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4"><div class="fs-1 fw-bold text-danger">{{ number_format($expense,0) }}</div><div class="text-muted">إجمالي المصروفات</div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4 {{ $net >= 0 ? 'border-success' : 'border-danger' }}"><div class="fs-1 fw-bold {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($net,0) }}</div><div class="text-muted">صافي الربح</div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-arrow-down me-2 text-success"></i>تفصيل الإيرادات</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>الفئة</th><th class="text-end">المبلغ (ج.م)</th></tr></thead>
                    <tbody>
                        @php $labels=['appointment_fee'=>'رسوم كشف','medicine_sale'=>'صيدلية','other'=>'أخرى']; @endphp
                        @foreach($byCategory->where('type','income') as $row)
                        <tr><td>{{ $labels[$row->category] ?? $row->category }}</td><td class="text-end text-success fw-bold">{{ number_format($row->total,0) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-arrow-up me-2 text-danger"></i>تفصيل المصروفات</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>الفئة</th><th class="text-end">المبلغ (ج.م)</th></tr></thead>
                    <tbody>
                        @php $elabels=['salary'=>'رواتب','rent'=>'إيجار','utilities'=>'مرافق','other'=>'أخرى']; @endphp
                        @foreach($byCategory->where('type','expense') as $row)
                        <tr><td>{{ $elabels[$row->category] ?? $row->category }}</td><td class="text-end text-danger fw-bold">{{ number_format($row->total,0) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
