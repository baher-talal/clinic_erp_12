@extends('layouts.app')
@section('title','سجل المكالمات')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.dashboard') }}">خدمة العملاء</a></li>
    <li class="breadcrumb-item active">المكالمات</li>
@endsection
@section('content')
{{-- Stats --}}
<div class="row g-3 mb-3">
    <div class="col-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold">{{ $todayStats['total'] }}</div><small class="text-muted">مكالمات اليوم</small></div></div>
    <div class="col-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-success">{{ $todayStats['answered'] }}</div><small class="text-muted">تم الرد</small></div></div>
    <div class="col-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-danger">{{ $todayStats['missed'] }}</div><small class="text-muted">فائتة</small></div></div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-phone me-2 text-primary"></i>سجل المكالمات</h5>
    <a href="{{ route('cs.calls.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> تسجيل مكالمة</a>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" placeholder="التاريخ"></div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- الحالة --</option>
                    <option value="answered" {{ request('status')=='answered' ? 'selected':'' }}>تم الرد</option>
                    <option value="missed" {{ request('status')=='missed' ? 'selected':'' }}>فائتة</option>
                    <option value="callback_needed" {{ request('status')=='callback_needed' ? 'selected':'' }}>تحتاج رد</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">-- النوع --</option>
                    <option value="incoming" {{ request('type')=='incoming' ? 'selected':'' }}>واردة</option>
                    <option value="outgoing" {{ request('type')=='outgoing' ? 'selected':'' }}>صادرة</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">بحث</button>
                <a href="{{ route('cs.calls.index') }}" class="btn btn-outline-secondary btn-sm">مسح</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>كود</th><th>المتصل</th><th>النوع</th><th>الغرض</th><th>الحالة</th><th>المدة</th><th>الوقت</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($calls as $call)
                    <tr>
                        <td><small class="text-muted">{{ $call->call_code }}</small></td>
                        <td>
                            <div class="fw-semibold small">{{ $call->patient?->name ?? $call->caller_name ?? 'غير مسجّل' }}</div>
                            <small class="text-muted">{{ $call->caller_phone }}</small>
                        </td>
                        <td><span class="badge bg-{{ $call->type==='incoming' ? 'success':'primary' }} role-badge">{{ $call->type==='incoming' ? 'واردة':'صادرة' }}</span></td>
                        <td><span class="badge bg-secondary role-badge">{{ $call->purpose_label }}</span></td>
                        <td><span class="badge bg-{{ $call->status_color }} role-badge">{{ $call->status_label }}</span></td>
                        <td><small>{{ $call->duration_formatted }}</small></td>
                        <td><small class="text-muted">{{ $call->called_at->format('d/m h:i A') }}</small></td>
                        <td><a href="{{ route('cs.calls.show', $call) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-phone-slash fa-3x mb-3 d-block"></i>لا توجد مكالمات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $calls->links() }}</div>
    </div>
</div>
@endsection
