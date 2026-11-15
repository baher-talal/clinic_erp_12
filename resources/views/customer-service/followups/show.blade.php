@extends('layouts.app')
@section('title','تفاصيل المتابعة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.followups.index') }}">المتابعات</a></li>
    <li class="breadcrumb-item active">{{ $followup->followup_code }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2 text-warning"></i>{{ $followup->followup_code }}</h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $followup->priority_color }}">{{ $followup->priority_label }}</span>
                    <span class="badge bg-{{ $followup->status==='completed'?'success':($followup->status==='in_progress'?'primary':'warning') }}">{{ $followup->status_label }}</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $followup->title }}</h5>
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">النوع</small><strong>{{ ['appointment_reminder'=>'تذكير موعد','post_treatment'=>'متابعة علاج','complaint'=>'شكوى','lab_results'=>'نتائج مختبر','other'=>'أخرى'][$followup->type] ?? $followup->type }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">موعد الإغلاق</small><strong class="{{ $followup->is_overdue ? 'text-danger' : '' }}">{{ $followup->due_date->format('d/m/Y h:i A') }}@if($followup->is_overdue) <span class="badge bg-danger">متأخر!</span>@endif</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">المسؤول</small><strong>{{ $followup->assignedTo?->name ?? '-' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">أُنشئت بواسطة</small><strong>{{ $followup->createdBy?->name ?? '-' }}</strong></div>
                    @if($followup->description)<div class="col-12"><small class="text-muted d-block">الوصف</small><p class="mb-0">{{ $followup->description }}</p></div>@endif
                    @if($followup->resolution_notes)<div class="col-12"><small class="text-muted d-block">ملاحظات الإغلاق</small><p class="mb-0 text-success">{{ $followup->resolution_notes }}</p></div>@endif
                </div>
            </div>
        </div>

        @if($followup->status !== 'completed')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small">تحديث الحالة</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        @foreach(['pending'=>['warning','معلق'],'in_progress'=>['primary','جارٍ'],'completed'=>['success','مكتمل'],'cancelled'=>['danger','ملغي']] as $s=>[$c,$l])
                        <form method="POST" action="{{ route('cs.followups.status', $followup) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $s }}">
                            <button class="btn btn-{{ $followup->status===$s ? $c : 'outline-'.$c }} btn-sm me-1 mb-1">{{ $l }}</button>
                        </form>
                        @endforeach
                    </div>
                </div>
                <form method="POST" action="{{ route('cs.followups.complete', $followup) }}">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">ملاحظات الإغلاق</label>
                        <textarea name="resolution_notes" class="form-control form-control-sm" rows="2" placeholder="ماذا تم؟"></textarea>
                    </div>
                    <button class="btn btn-success"><i class="fas fa-check me-1"></i> إغلاق المتابعة</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>تم إغلاق هذه المتابعة بتاريخ {{ $followup->completed_at?->format('d/m/Y h:i A') }}</div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($followup->patient)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-user me-2 text-primary"></i>المريض</div>
            <div class="card-body">
                <div class="fw-bold">{{ $followup->patient->name }}</div>
                <small class="text-muted d-block">{{ $followup->patient->patient_code }}</small>
                <small class="text-muted d-block"><i class="fas fa-phone me-1"></i>{{ $followup->patient->phone }}</small>
                <div class="mt-2 d-flex gap-2">
                    <a href="{{ route('patients.show', $followup->patient) }}" class="btn btn-sm btn-outline-primary">الملف</a>
                    <a href="{{ route('appointments.create') }}?patient_id={{ $followup->patient->id }}" class="btn btn-sm btn-success">حجز موعد</a>
                </div>
            </div>
        </div>
        @endif
        @if($followup->call)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-phone me-2 text-info"></i>المكالمة المرتبطة</div>
            <div class="card-body">
                <div class="fw-bold small">{{ $followup->call->call_code }}</div>
                <small class="text-muted d-block">{{ $followup->call->purpose_label }}</small>
                <small class="text-muted d-block">{{ $followup->call->called_at->format('d/m/Y h:i A') }}</small>
                <a href="{{ route('cs.calls.show', $followup->call) }}" class="btn btn-sm btn-outline-info mt-2">عرض المكالمة</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
