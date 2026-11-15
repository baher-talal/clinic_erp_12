@extends('layouts.app')
@section('title', 'ملف المريض - ' . $patient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">المرضى</a></li>
    <li class="breadcrumb-item active">{{ $patient->name }}</li>
@endsection

@section('content')
<div class="row g-3">
    {{-- بيانات المريض --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width:80px;height:80px;font-size:2rem">
                    <i class="fas fa-{{ $patient->gender === 'male' ? 'male' : 'female' }} text-primary"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $patient->name }}</h5>
                <span class="badge bg-secondary mb-2">{{ $patient->patient_code }}</span>
                @if($patient->blood_type)
                    <span class="badge bg-danger ms-1">{{ $patient->blood_type }}</span>
                @endif
                <div class="mt-3 d-flex gap-2 justify-content-center">
                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </a>
                    <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-calendar-plus me-1"></i> موعد جديد
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-id-card me-2 text-primary"></i>البيانات الشخصية
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">الجنس</td><td>{{ $patient->gender_label }}</td></tr>
                    <tr><td class="text-muted">العمر</td><td>{{ $patient->age }} سنة</td></tr>
                    <tr><td class="text-muted">تاريخ الميلاد</td><td>{{ $patient->birth_date->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">الهاتف</td><td>{{ $patient->phone }}</td></tr>
                    @if($patient->phone_emergency)
                    <tr><td class="text-muted">طوارئ</td><td>{{ $patient->phone_emergency }}</td></tr>
                    @endif
                    @if($patient->national_id)
                    <tr><td class="text-muted">الرقم القومي</td><td>{{ $patient->national_id }}</td></tr>
                    @endif
                    @if($patient->address)
                    <tr><td class="text-muted">العنوان</td><td>{{ $patient->address }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        @if($patient->allergies || $patient->chronic_diseases)
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-heartbeat me-2 text-danger"></i>معلومات طبية مهمة
            </div>
            <div class="card-body">
                @if($patient->allergies)
                <div class="mb-2">
                    <small class="text-muted d-block fw-semibold">الحساسية:</small>
                    <span class="text-danger">{{ $patient->allergies }}</span>
                </div>
                @endif
                @if($patient->chronic_diseases)
                <div>
                    <small class="text-muted d-block fw-semibold">الأمراض المزمنة:</small>
                    <span>{{ $patient->chronic_diseases }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- تاريخ المواعيد والسجلات --}}
    <div class="col-lg-8">
        {{-- إحصائيات --}}
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="fs-3 fw-bold text-primary">{{ $patient->appointments->count() }}</div>
                    <small class="text-muted">إجمالي المواعيد</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="fs-3 fw-bold text-success">{{ $patient->medicalRecords->count() }}</div>
                    <small class="text-muted">سجلات طبية</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="fs-3 fw-bold text-warning">{{ $patient->prescriptions->count() }}</div>
                    <small class="text-muted">روشتات</small>
                </div>
            </div>
        </div>

        {{-- آخر المواعيد --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>سجل المواعيد
            </div>
            <div class="card-body p-0">
                @forelse($patient->appointments->sortByDesc('appointment_date')->take(10) as $appt)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="text-center" style="min-width:50px">
                        <div class="fw-bold">{{ $appt->appointment_date->format('d') }}</div>
                        <small class="text-muted">{{ $appt->appointment_date->format('M Y') }}</small>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">د. {{ $appt->doctor->user->name ?? '-' }}</div>
                        <small class="text-muted">{{ $appt->type_label }}</small>
                    </div>
                    <div>
                        <span class="badge bg-{{ $appt->status_color }}">{{ $appt->status_label }}</span>
                    </div>
                    <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>لا توجد مواعيد
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
