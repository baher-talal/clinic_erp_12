@extends('layouts.app')
@section('title','ملف المريض')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('doctor.patients') }}">مرضاي</a></li>
    <li class="breadcrumb-item active">{{ $patient->name }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold text-primary" style="width:70px;height:70px;font-size:1.5rem">
                    {{ mb_substr($patient->name,0,1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $patient->name }}</h5>
                <span class="badge bg-secondary mb-1">{{ $patient->patient_code }}</span>
                @if($patient->blood_type)<span class="badge bg-danger ms-1">{{ $patient->blood_type }}</span>@endif
                <div class="mt-2 text-muted small">{{ $patient->gender_label }} • {{ $patient->age }} سنة</div>
                <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $patient->phone }}</div>
            </div>
        </div>
        @if($patient->allergies || $patient->chronic_diseases)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-heartbeat me-2 text-danger"></i>معلومات طبية</div>
            <div class="card-body">
                @if($patient->allergies)<div class="mb-2"><small class="text-muted fw-semibold d-block">الحساسية:</small><span class="text-danger small">{{ $patient->allergies }}</span></div>@endif
                @if($patient->chronic_diseases)<div><small class="text-muted fw-semibold d-block">الأمراض المزمنة:</small><span class="small">{{ $patient->chronic_diseases }}</span></div>@endif
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-file-medical me-2 text-success"></i>السجلات الطبية ({{ $medicalRecords->count() }})</div>
            <div class="card-body p-0">
                @forelse($medicalRecords as $record)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <small class="text-muted">{{ $record->created_at->format('d/m/Y') }}</small>
                        @if($record->prescription)<span class="badge bg-warning role-badge">روشتة: {{ $record->prescription->status_label }}</span>@endif
                    </div>
                    <div class="mb-1"><small class="text-muted">الشكوى:</small> <span class="small">{{ $record->complaint }}</span></div>
                    <div class="mb-2"><small class="text-muted">التشخيص:</small> <span class="small fw-semibold">{{ $record->diagnosis }}</span></div>
                    <a href="{{ route('records.show', $record) }}" class="btn btn-sm btn-outline-success">عرض السجل الكامل</a>
                </div>
                @empty
                <div class="text-center py-5 text-muted"><i class="fas fa-file-medical fa-3x mb-3 d-block"></i>لا توجد سجلات طبية</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
