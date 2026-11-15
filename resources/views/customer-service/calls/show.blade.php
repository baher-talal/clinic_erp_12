@extends('layouts.app')
@section('title','تفاصيل المكالمة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.calls.index') }}">المكالمات</a></li>
    <li class="breadcrumb-item active">{{ $call->call_code }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-phone me-2 text-primary"></i>{{ $call->call_code }}</h6>
                <span class="badge bg-{{ $call->status_color }}">{{ $call->status_label }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">المتصل</small><strong>{{ $call->patient?->name ?? $call->caller_name ?? 'غير مسجّل' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">الهاتف</small><strong><a href="tel:{{ $call->caller_phone }}">{{ $call->caller_phone }}</a></strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">النوع</small><span class="badge bg-{{ $call->type==='incoming' ? 'success':'primary' }}">{{ $call->type==='incoming' ? 'واردة':'صادرة' }}</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">الغرض</small><strong>{{ $call->purpose_label }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">الوقت</small><strong>{{ $call->called_at->format('d/m/Y h:i A') }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">المدة</small><strong>{{ $call->duration_formatted }}</strong></div>
                    @if($call->notes)<div class="col-12"><small class="text-muted d-block">ملاحظات</small><p class="mb-0">{{ $call->notes }}</p></div>@endif
                </div>
            </div>
        </div>

        {{-- تحديث الحالة --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-edit me-2"></i>تحديث المكالمة</div>
            <div class="card-body">
                <form method="POST" action="{{ route('cs.calls.update', $call) }}">
                    @csrf @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="status" class="form-select form-select-sm">
                                @foreach(['answered'=>'تم الرد','missed'=>'فائتة','callback_needed'=>'تحتاج رد','resolved'=>'محلولة'] as $v=>$l)
                                <option value="{{ $v }}" {{ $call->status===$v ? 'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><input type="text" name="notes" class="form-control form-control-sm" value="{{ $call->notes }}" placeholder="ملاحظات..."></div>
                        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">حفظ</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- المتابعات المرتبطة --}}
        @if($call->followups->isNotEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-tasks me-2"></i>المتابعات المرتبطة</div>
            <div class="card-body p-0">
                @foreach($call->followups as $fu)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $fu->title }}</div>
                        <small class="text-muted">{{ $fu->assignedTo?->name }} • {{ $fu->due_date->format('d/m h:i A') }}</small>
                    </div>
                    <span class="badge bg-{{ $fu->priority_color }} role-badge">{{ $fu->priority_label }}</span>
                    <span class="badge bg-{{ $fu->status==='completed' ? 'success':'warning' }} role-badge">{{ $fu->status_label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($call->patient)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-user me-2 text-primary"></i>بيانات المريض</div>
            <div class="card-body">
                <div class="fw-bold">{{ $call->patient->name }}</div>
                <small class="text-muted d-block">{{ $call->patient->patient_code }}</small>
                <small class="text-muted d-block">العمر: {{ $call->patient->age }} سنة</small>
                <div class="mt-2 d-flex gap-2">
                    <a href="{{ route('patients.show', $call->patient) }}" class="btn btn-sm btn-outline-primary">الملف</a>
                    <a href="{{ route('appointments.create') }}?patient_id={{ $call->patient->id }}" class="btn btn-sm btn-success">حجز موعد</a>
                </div>
            </div>
        </div>
        @endif
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <a href="{{ route('cs.followups.create') }}?call_id={{ $call->id }}" class="btn btn-warning w-100">
                    <i class="fas fa-tasks me-1"></i> إنشاء متابعة لهذه المكالمة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
