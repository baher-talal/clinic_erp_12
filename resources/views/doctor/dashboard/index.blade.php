@extends('layouts.app')
@section('title','لوحة الطبيب')
@section('breadcrumb')
    <li class="breadcrumb-item active">لوحتي</li>
@endsection
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;font-size:1.5rem">
        <i class="fas fa-user-md text-primary"></i>
    </div>
    <div>
        <h5 class="fw-bold mb-0">مرحباً، د. {{ auth()->user()->name }}</h5>
        <small class="text-muted">{{ $doctor->specialization }} — {{ now()->format('l، d F Y') }}</small>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10"><i class="fas fa-calendar-day text-primary"></i></div>
                <div><div class="fs-2 fw-bold">{{ $todayAppointments->count() }}</div><div class="text-muted small">مواعيد اليوم</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10"><i class="fas fa-clock text-warning"></i></div>
                <div><div class="fs-2 fw-bold">{{ $pendingCount }}</div><div class="text-muted small">في الانتظار</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10"><i class="fas fa-check-circle text-success"></i></div>
                <div><div class="fs-2 fw-bold">{{ $completedToday }}</div><div class="text-muted small">أُنجز اليوم</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10"><i class="fas fa-users text-info"></i></div>
                <div><div class="fs-2 fw-bold">{{ $totalPatients }}</div><div class="text-muted small">إجمالي مرضاي</div></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- جدول اليوم --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-list-alt me-2 text-primary"></i>جدول اليوم
            </div>
            <div class="card-body p-0">
                @forelse($todayAppointments as $appt)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom {{ $appt->status === 'in_progress' ? 'bg-primary bg-opacity-5' : '' }}">
                    <div class="text-center bg-light rounded p-2" style="min-width:55px">
                        <div class="fw-bold text-primary">{{ $appt->appointment_date->format('h:i') }}</div>
                        <small class="text-muted">{{ $appt->appointment_date->format('A') }}</small>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $appt->patient->name }}</div>
                        <small class="text-muted">{{ $appt->patient->patient_code }} • {{ $appt->type_label }}</small>
                    </div>
                    <span class="badge bg-{{ $appt->status_color }}">{{ $appt->status_label }}</span>
                    <div class="d-flex gap-1">
                        @if(in_array($appt->status, ['pending','confirmed','in_progress']) && !$appt->medicalRecord)
                        <a href="{{ route('records.create', $appt) }}" class="btn btn-sm btn-success" title="بدء الكشف">
                            <i class="fas fa-stethoscope"></i>
                        </a>
                        @endif
                        <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-check fa-3x mb-3 d-block text-success"></i>
                    <p>لا توجد مواعيد اليوم</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- المواعيد القادمة --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-calendar-alt me-2 text-info"></i>المواعيد القادمة
            </div>
            <div class="card-body p-0">
                @forelse($upcomingAppointments as $appt)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold small">{{ $appt->patient->name }}</div>
                            <small class="text-muted">{{ $appt->appointment_date->format('d/m - h:i A') }}</small>
                        </div>
                        <span class="badge bg-{{ $appt->status_color }} role-badge">{{ $appt->status_label }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">لا توجد مواعيد قادمة</div>
                @endforelse
                <div class="p-2">
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-outline-primary w-100">عرض الكل</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
