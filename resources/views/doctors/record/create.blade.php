@extends('layouts.app')
@section('title', 'إنشاء سجل طبي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('appointments.show', $appointment) }}">{{ $appointment->appointment_code }}</a></li>
    <li class="breadcrumb-item active">سجل طبي</li>
@endsection

@section('content')
<div class="row g-3">
    {{-- بيانات المريض --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-user me-2 text-primary"></i>المريض</div>
            <div class="card-body">
                <div class="fw-bold">{{ $appointment->patient->name }}</div>
                <small class="text-muted d-block">{{ $appointment->patient->patient_code }}</small>
                <small class="text-muted d-block">العمر: {{ $appointment->patient->age }} سنة</small>
                @if($appointment->patient->blood_type)
                    <span class="badge bg-danger mt-1">{{ $appointment->patient->blood_type }}</span>
                @endif
                @if($appointment->patient->allergies)
                <div class="alert alert-warning mt-2 p-2 small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>حساسية:</strong> {{ $appointment->patient->allergies }}
                </div>
                @endif
                @if($appointment->patient->chronic_diseases)
                <div class="alert alert-info mt-2 p-2 small">
                    <i class="fas fa-heartbeat me-1"></i>
                    {{ $appointment->patient->chronic_diseases }}
                </div>
                @endif
            </div>
        </div>

        {{-- السجلات السابقة --}}
        @if($previousRecords->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small"><i class="fas fa-history me-2 text-info"></i>آخر الزيارات</div>
            <div class="card-body p-0">
                @foreach($previousRecords as $prev)
                <div class="p-2 border-bottom">
                    <small class="text-muted d-block">{{ $prev->created_at->format('d/m/Y') }}</small>
                    <small class="d-block text-truncate">{{ $prev->diagnosis }}</small>
                    <a href="{{ route('records.show', $prev) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.7rem;padding:1px 6px">عرض</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- نموذج السجل --}}
    <div class="col-lg-9">
        <form action="{{ route('records.store', $appointment) }}" method="POST" id="recordForm">
            @csrf

            {{-- القياسات --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-ruler me-2 text-info"></i>قياسات جسم المريض
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-semibold">الوزن (كجم)</label>
                            <input type="number" name="weight" class="form-control form-control-sm" step="0.1" min="1" max="300" placeholder="70">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-semibold">الطول (سم)</label>
                            <input type="number" name="height" class="form-control form-control-sm" step="0.1" min="50" max="250" placeholder="170">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-semibold">ضغط الدم</label>
                            <input type="text" name="blood_pressure" class="form-control form-control-sm" placeholder="120/80">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-semibold">الحرارة (°م)</label>
                            <input type="number" name="temperature" class="form-control form-control-sm" step="0.1" min="35" max="42" placeholder="37">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-semibold">النبض (ن/د)</label>
                            <input type="number" name="pulse" class="form-control form-control-sm" min="40" max="200" placeholder="80">
                        </div>
                    </div>
                </div>
            </div>

            {{-- التشخيص --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-stethoscope me-2 text-success"></i>الفحص والتشخيص
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">الشكوى الرئيسية <span class="text-danger">*</span></label>
                        <textarea name="complaint" class="form-control @error('complaint') is-invalid @enderror"
                            rows="3" placeholder="ما يشكو منه المريض..." required>{{ old('complaint') }}</textarea>
                        @error('complaint')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">التشخيص <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror"
                            rows="3" placeholder="التشخيص الطبي..." required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">خطة العلاج</label>
                        <textarea name="treatment_plan" class="form-control" rows="2" placeholder="خطة العلاج المقترحة...">{{ old('treatment_plan') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">ملاحظات إضافية</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- الروشتة --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-prescription me-2 text-warning"></i>الروشتة الطبية</span>
                    <button type="button" class="btn btn-warning btn-sm" id="addMedBtn">
                        <i class="fas fa-plus me-1"></i> إضافة دواء
                    </button>
                </div>
                <div class="card-body">
                    <div id="medicinesContainer"></div>
                    <div id="emptyMeds" class="text-center py-3 text-muted">
                        <i class="fas fa-pills fa-2x mb-2 d-block"></i>
                        <small>اضغط "إضافة دواء" لإضافة أدوية للروشتة</small>
                    </div>
                    <div class="mt-3" id="instructionsDiv" style="display:none">
                        <label class="form-label small fw-semibold">تعليمات عامة للروشتة</label>
                        <textarea name="prescription_instructions" class="form-control form-control-sm" rows="2" placeholder="مثل: تناول الدواء بعد الأكل..."></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-1"></i> حفظ السجل الطبي
                </button>
                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const medicines = @json($medicines);
let medCount = 0;

document.getElementById('addMedBtn').addEventListener('click', function() {
    medCount++;
    document.getElementById('emptyMeds').style.display = 'none';
    document.getElementById('instructionsDiv').style.display = 'block';

    const opts = medicines.map(m => `<option value="${m.id}">${m.name} (${m.unit})</option>`).join('');
    const html = `
    <div class="border rounded p-3 mb-2 med-row" id="med_${medCount}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">الدواء *</label>
                <select name="medicines[${medCount}][medicine_id]" class="form-select form-select-sm" required>
                    <option value="">-- اختر --</option>${opts}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">الجرعة *</label>
                <input type="text" name="medicines[${medCount}][dosage]" class="form-control form-control-sm" placeholder="حبة واحدة" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">مرات التكرار *</label>
                <input type="text" name="medicines[${medCount}][frequency]" class="form-control form-control-sm" placeholder="3 مرات يومياً" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">عدد الأيام *</label>
                <input type="number" name="medicines[${medCount}][duration_days]" class="form-control form-control-sm" min="1" value="7" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('med_${medCount}').remove(); if(!document.querySelectorAll('.med-row').length){ document.getElementById('emptyMeds').style.display='block'; document.getElementById('instructionsDiv').style.display='none'; }">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>`;
    document.getElementById('medicinesContainer').insertAdjacentHTML('beforeend', html);
});
</script>
@endpush
