@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10">
                    <i class="fas fa-calendar-check text-primary"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ $todayAppointments }}</div>
                    <div class="text-muted small">مواعيد اليوم</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10">
                    <i class="fas fa-dollar-sign text-success"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ number_format($todayIncome) }}</div>
                    <div class="text-muted small">إيرادات اليوم (ج.م)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10">
                    <i class="fas fa-users text-info"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ $totalPatients }}</div>
                    <div class="text-muted small">إجمالي المرضى</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10">
                    <i class="fas fa-clock text-warning"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ $pendingPayments }}</div>
                    <div class="text-muted small">مواعيد غير مدفوعة</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Today's Appointments --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-list-alt me-2 text-primary"></i>مواعيد اليوم</h6>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> موعد جديد
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentAppointments->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>لا توجد مواعيد اليوم</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>المريض</th>
                                    <th>الطبيب</th>
                                    <th>الوقت</th>
                                    <th>الحالة</th>
                                    <th>الدفع</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAppointments as $appt)
                                <tr>
                                    <td><small class="text-muted">{{ $appt->appointment_code }}</small></td>
                                    <td>{{ $appt->patient->name }}</td>
                                    <td>{{ $appt->doctor->user->name }}</td>
                                    <td>{{ $appt->appointment_date->format('h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appt->status_color }} badge-status">
                                            {{ $appt->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($appt->payment_status === 'paid')
                                            <span class="badge bg-success badge-status">مدفوع</span>
                                        @else
                                            <span class="badge bg-danger badge-status">غير مدفوع</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-info"></i>معلومات سريعة</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted small">الأطباء النشطون</span>
                    <span class="fw-bold text-primary">{{ $totalDoctors }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted small">أدوية منخفضة المخزون</span>
                    <span class="fw-bold {{ $lowStockMedicines > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $lowStockMedicines }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small">مدفوعات معلقة</span>
                    <span class="fw-bold text-warning">{{ $pendingPayments }}</span>
                </div>
            </div>
        </div>

        @if($lowStockMedicines > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            يوجد <strong>{{ $lowStockMedicines }}</strong> دواء وصل للحد الأدنى
            <a href="{{ route('pharmacy.low-stock') }}" class="alert-link d-block mt-1">عرض التفاصيل ←</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// يمكن إضافة رسوم بيانية هنا
</script>
@endpush
