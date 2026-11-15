@extends('layouts.app')
@section('title', 'التقارير المالية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('treasury.index') }}">الخزنة</a></li>
    <li class="breadcrumb-item active">التقارير</li>
@endsection

@section('content')
{{-- فلتر التاريخ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">من تاريخ</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">إلى تاريخ</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">عرض التقرير</button>
            </div>
        </form>
    </div>
</div>

{{-- ملخص الفترة --}}
@php
    $income  = $summary->where('type','income')->sum('total');
    $expense = $summary->where('type','expense')->sum('total');
    $net     = $income - $expense;
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ number_format($income, 2) }}</div>
            <div class="text-muted">إجمالي الإيرادات (ج.م)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-danger">{{ number_format($expense, 2) }}</div>
            <div class="text-muted">إجمالي المصروفات (ج.م)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($net, 2) }}</div>
            <div class="text-muted">صافي الربح (ج.م)</div>
        </div>
    </div>
</div>

{{-- تفصيل بالفئات --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-arrow-down me-2 text-success"></i>تفصيل الإيرادات
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>الفئة</th><th class="text-end">المبلغ</th></tr></thead>
                    <tbody>
                        @foreach($summary->where('type','income') as $row)
                        @php $labels=['appointment_fee'=>'رسوم كشف','medicine_sale'=>'صيدلية','other'=>'أخرى'] @endphp
                        <tr>
                            <td>{{ $labels[$row->category] ?? $row->category }}</td>
                            <td class="text-end text-success fw-semibold">{{ number_format($row->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-arrow-up me-2 text-danger"></i>تفصيل المصروفات
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>الفئة</th><th class="text-end">المبلغ</th></tr></thead>
                    <tbody>
                        @foreach($summary->where('type','expense') as $row)
                        @php $labels=['salary'=>'رواتب','rent'=>'إيجار','utilities'=>'مرافق','other'=>'أخرى'] @endphp
                        <tr>
                            <td>{{ $labels[$row->category] ?? $row->category }}</td>
                            <td class="text-end text-danger fw-semibold">{{ number_format($row->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
