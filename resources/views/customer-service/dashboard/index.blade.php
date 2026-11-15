@extends('layouts.app')
@section('title','خدمة العملاء')
@section('breadcrumb')
    <li class="breadcrumb-item active">خدمة العملاء</li>
@endsection
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;font-size:1.5rem">
        <i class="fas fa-headset text-info"></i>
    </div>
    <div>
        <h5 class="fw-bold mb-0">مرحباً، {{ auth()->user()->name }}</h5>
        <small class="text-muted">خدمة العملاء — {{ now()->format('l، d F Y') }}</small>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10"><i class="fas fa-phone text-primary"></i></div>
                <div><div class="fs-2 fw-bold">{{ $todayCalls }}</div><div class="text-muted small">مكالمات اليوم</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10"><i class="fas fa-phone-slash text-danger"></i></div>
                <div><div class="fs-2 fw-bold">{{ $missedCalls }}</div><div class="text-muted small">مكالمات فائتة</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10"><i class="fas fa-tasks text-warning"></i></div>
                <div><div class="fs-2 fw-bold">{{ $pendingFollowups }}</div><div class="text-muted small">متابعاتي المعلقة</div></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10"><i class="fas fa-exclamation-circle text-danger"></i></div>
                <div><div class="fs-2 fw-bold">{{ $overdueFollowups }}</div><div class="text-muted small">متابعات متأخرة</div></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- آخر المكالمات --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-phone me-2 text-primary"></i>آخر المكالمات</span>
                <a href="{{ route('cs.calls.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> تسجيل مكالمة
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($recentCalls as $call)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="stat-icon bg-{{ $call->status_color }} bg-opacity-10" style="width:38px;height:38px;border-radius:8px;font-size:.9rem;flex-shrink:0">
                        <i class="fas fa-{{ $call->type === 'incoming' ? 'phone-alt' : 'phone' }} text-{{ $call->status_color }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $call->patient?->name ?? $call->caller_name ?? 'غير مسجّل' }}</div>
                        <small class="text-muted">{{ $call->caller_phone }} • {{ $call->purpose_label }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $call->status_color }} role-badge">{{ $call->status_label }}</span>
                        <div class="text-muted" style="font-size:.7rem">{{ $call->called_at->diffForHumans() }}</div>
                    </div>
                    <a href="{{ route('cs.calls.show', $call) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-phone-slash fa-3x mb-3 d-block"></i>لا توجد مكالمات اليوم
                </div>
                @endforelse
                <div class="p-2 text-center">
                    <a href="{{ route('cs.calls.index') }}" class="btn btn-sm btn-outline-primary">عرض كل المكالمات</a>
                </div>
            </div>
        </div>
    </div>

    {{-- المتابعات العاجلة --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-fire me-2 text-danger"></i>متابعات عاجلة</span>
                <a href="{{ route('cs.followups.create') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-plus me-1"></i> متابعة
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($urgentFollowups as $fu)
                <div class="p-3 border-bottom {{ $fu->is_overdue ? 'bg-danger bg-opacity-5' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-semibold small">{{ $fu->title }}</span>
                        <span class="badge bg-{{ $fu->priority_color }} role-badge">{{ $fu->priority_label }}</span>
                    </div>
                    <small class="text-muted d-block">{{ $fu->patient?->name ?? 'عميل' }}</small>
                    <small class="{{ $fu->is_overdue ? 'text-danger fw-bold' : 'text-muted' }}">
                        <i class="fas fa-clock me-1"></i>{{ $fu->due_date->format('d/m h:i A') }}
                        @if($fu->is_overdue) (متأخر!) @endif
                    </small>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">
                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                    لا توجد متابعات عاجلة
                </div>
                @endforelse
                <div class="p-2 text-center">
                    <a href="{{ route('cs.followups.index') }}" class="btn btn-sm btn-outline-warning">عرض كل المتابعات</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
