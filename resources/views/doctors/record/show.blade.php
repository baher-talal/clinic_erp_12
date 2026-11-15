@extends('layouts.app')
@section('title', 'السجل الطبي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('appointments.show', $record->appointment) }}">الموعد</a></li>
    <li class="breadcrumb-item active">السجل الطبي</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-medical me-2 text-success"></i>السجل الطبي</h6>
                <small class="text-muted">{{ $record->created_at->format('d/m/Y h:i A') }}</small>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">الشكوى الرئيسية</label>
                        <p class="border rounded p-2 bg-light">{{ $record->complaint }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">التشخيص</label>
                        <p class="border rounded p-2 bg-light">{{ $record->diagnosis }}</p>
                    </div>
                    @if($record->treatment_plan)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">خطة العلاج</label>
                        <p class="border rounded p-2 bg-light">{{ $record->treatment_plan }}</p>
                    </div>
                    @endif
                </div>

                {{-- القياسات --}}
                @if($record->weight || $record->blood_pressure || $record->temperature)
                <h6 class="fw-bold text-info mb-3"><i class="fas fa-ruler me-1"></i>القياسات الحيوية</h6>
                <div class="row g-2 mb-3">
                    @foreach([['الوزن','weight','كجم'],['الطول','height','سم'],['ضغط الدم','blood_pressure',''],['الحرارة','temperature','°م'],['النبض','pulse','ن/د']] as $m)
                    @if($record->{$m[1]})
                    <div class="col-6 col-md-2">
                        <div class="card text-center p-2 border">
                            <div class="fw-bold">{{ $record->{$m[1]} }} <small>{{ $m[2] }}</small></div>
                            <small class="text-muted">{{ $m[0] }}</small>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @if($record->bmi)
                    <div class="col-6 col-md-2">
                        <div class="card text-center p-2 border {{ $record->bmi < 18.5 || $record->bmi > 30 ? 'border-warning' : 'border-success' }}">
                            <div class="fw-bold">{{ $record->bmi }}</div>
                            <small class="text-muted">BMI</small>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- الروشتة --}}
        @if($record->prescription)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-prescription me-2 text-warning"></i>الروشتة الطبية</h6>
                <span class="badge bg-{{ $record->prescription->status === 'dispensed' ? 'success' : ($record->prescription->status === 'cancelled' ? 'danger' : 'warning') }}">
                    {{ $record->prescription->status_label }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الدواء</th>
                                <th>الجرعة</th>
                                <th>التكرار</th>
                                <th>المدة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->prescription->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->medicine->name }}</td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->frequency }}</td>
                                <td>{{ $item->duration_days }} يوم</td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($record->prescription->instructions)
                <div class="p-3 bg-light">
                    <small class="fw-semibold">تعليمات:</small> {{ $record->prescription->instructions }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-user me-2 text-primary"></i>المريض</div>
            <div class="card-body">
                <div class="fw-bold">{{ $record->patient->name }}</div>
                <small class="text-muted d-block">{{ $record->patient->patient_code }}</small>
                <small class="text-muted d-block">العمر: {{ $record->patient->age }} سنة</small>
                <a href="{{ route('patients.show', $record->patient) }}" class="btn btn-sm btn-outline-primary mt-2">
                    الملف الكامل
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
