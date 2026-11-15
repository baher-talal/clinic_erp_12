@extends('layouts.app')
@section('title', 'تنبيهات المخزون')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pharmacy.index') }}">الصيدلية</a></li>
    <li class="breadcrumb-item active">تنبيهات المخزون</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>أدوية منخفضة المخزون
    </h5>
    <span class="badge bg-danger fs-6">{{ $medicines->count() }} دواء</span>
</div>

@if($medicines->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3 d-block"></i>
        <h5 class="text-success">المخزون بحالة جيدة</h5>
        <p class="text-muted">لا توجد أدوية وصلت للحد الأدنى</p>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الدواء</th>
                        <th>الفئة</th>
                        <th>الكمية الحالية</th>
                        <th>الحد الأدنى</th>
                        <th>الحالة</th>
                        <th>انتهاء الصلاحية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $med)
                    <tr class="{{ $med->quantity_in_stock == 0 ? 'table-danger' : 'table-warning' }}">
                        <td>
                            <div class="fw-bold">{{ $med->name }}</div>
                            @if($med->generic_name)<small class="text-muted">{{ $med->generic_name }}</small>@endif
                        </td>
                        <td>{{ $med->category->name }}</td>
                        <td>
                            <span class="badge bg-{{ $med->quantity_in_stock == 0 ? 'danger' : 'warning text-dark' }} fs-6">
                                {{ $med->quantity_in_stock }} {{ $med->unit }}
                            </span>
                        </td>
                        <td>{{ $med->min_stock_alert }} {{ $med->unit }}</td>
                        <td>
                            @if($med->quantity_in_stock == 0)
                                <span class="badge bg-danger">نفد من المخزون</span>
                            @else
                                <span class="badge bg-warning text-dark">مخزون منخفض</span>
                            @endif
                        </td>
                        <td>
                            @if($med->expiry_date)
                                <span class="{{ $med->expiry_status === 'expired' ? 'text-danger fw-bold' : '' }}">
                                    {{ $med->expiry_date->format('m/Y') }}
                                    @if($med->expiry_status === 'expired') (منتهي!) @endif
                                </span>
                            @else -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
