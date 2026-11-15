@extends('layouts.app')
@section('title','المتابعات')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.dashboard') }}">خدمة العملاء</a></li>
    <li class="breadcrumb-item active">المتابعات</li>
@endsection
@section('content')
{{-- Stats --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-warning">{{ $stats['pending'] }}</div><small class="text-muted">معلقة</small></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-danger">{{ $stats['overdue'] }}</div><small class="text-muted">متأخرة</small></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-primary">{{ $stats['today_due'] }}</div><small class="text-muted">تستحق اليوم</small></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-3 fw-bold text-info">{{ $stats['mine'] }}</div><small class="text-muted">الخاصة بي</small></div></div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-tasks me-2 text-warning"></i>إدارة المتابعات</h5>
    <a href="{{ route('cs.followups.create') }}" class="btn btn-warning btn-sm"><i class="fas fa-plus me-1"></i> متابعة جديدة</a>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- الحالة --</option>
                    <option value="pending"     {{ request('status')=='pending'     ? 'selected':'' }}>معلق</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>جارٍ</option>
                    <option value="completed"   {{ request('status')=='completed'   ? 'selected':'' }}>مكتمل</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">-- الأولوية --</option>
                    <option value="urgent" {{ request('priority')=='urgent' ? 'selected':'' }}>عاجل</option>
                    <option value="high"   {{ request('priority')=='high'   ? 'selected':'' }}>عالي</option>
                    <option value="medium" {{ request('priority')=='medium' ? 'selected':'' }}>متوسط</option>
                    <option value="low"    {{ request('priority')=='low'    ? 'selected':'' }}>منخفض</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="d-flex align-items-center gap-2 border rounded p-1 px-2 form-select-sm" style="cursor:pointer">
                    <input type="checkbox" name="mine" value="1" {{ request('mine') ? 'checked':'' }}>
                    <small>خاصتي فقط</small>
                </label>
            </div>
            <div class="col-md-2">
                <label class="d-flex align-items-center gap-2 border rounded p-1 px-2 form-select-sm border-danger" style="cursor:pointer">
                    <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked':'' }}>
                    <small class="text-danger">المتأخرة فقط</small>
                </label>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">بحث</button>
                <a href="{{ route('cs.followups.index') }}" class="btn btn-outline-secondary btn-sm">مسح</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>كود</th><th>العنوان</th><th>المريض</th><th>المسؤول</th><th>الأولوية</th><th>الحالة</th><th>موعد الإغلاق</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($followups as $fu)
                    <tr class="{{ $fu->is_overdue ? 'table-danger' : '' }}">
                        <td><small class="text-muted">{{ $fu->followup_code }}</small></td>
                        <td>
                            <div class="fw-semibold small">{{ $fu->title }}</div>
                            <small class="text-muted">{{ $fu->type === 'appointment_reminder' ? 'تذكير موعد' : ($fu->type === 'complaint' ? 'شكوى' : ($fu->type === 'post_treatment' ? 'متابعة علاج' : 'أخرى')) }}</small>
                        </td>
                        <td><small>{{ $fu->patient?->name ?? '-' }}</small></td>
                        <td><small>{{ $fu->assignedTo?->name ?? '-' }}</small></td>
                        <td><span class="badge bg-{{ $fu->priority_color }} role-badge">{{ $fu->priority_label }}</span></td>
                        <td><span class="badge bg-{{ $fu->status==='completed' ? 'success':($fu->status==='in_progress' ? 'primary':'warning') }} role-badge">{{ $fu->status_label }}</span></td>
                        <td>
                            <small class="{{ $fu->is_overdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ $fu->due_date->format('d/m/Y h:i A') }}
                                @if($fu->is_overdue)<span class="badge bg-danger role-badge">متأخر</span>@endif
                            </small>
                        </td>
                        <td>
                            <a href="{{ route('cs.followups.show', $fu) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-check-double fa-3x mb-3 d-block text-success"></i>لا توجد متابعات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $followups->links() }}</div>
    </div>
</div>
@endsection
