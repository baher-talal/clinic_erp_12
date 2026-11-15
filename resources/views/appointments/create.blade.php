@extends('layouts.app')
@section('title', 'حجز موعد جديد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">المواعيد</a></li>
    <li class="breadcrumb-item active">موعد جديد</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm" style="max-width:720px;margin:auto">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-plus me-2 text-primary"></i>حجز موعد جديد</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                {{-- اختيار المريض --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">المريض <span class="text-danger">*</span></label>
                    <select name="patient_id" id="patientSelect" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- ابحث واختر مريضاً --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ (old('patient_id') ?? request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->patient_code }} - {{ $patient->name }} ({{ $patient->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- اختيار الطبيب --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الطبيب <span class="text-danger">*</span></label>
                    <select name="doctor_id" id="doctorSelect" class="form-select @error('doctor_id') is-invalid @enderror" required>
                        <option value="">-- اختر الطبيب --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                data-fee="{{ $doctor->consultation_fee }}"
                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                د. {{ $doctor->user->name }} - {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- نوع الكشف --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">نوع الكشف <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="new" {{ old('type','new') == 'new' ? 'selected' : '' }}>كشف جديد</option>
                        <option value="follow_up" {{ old('type') == 'follow_up' ? 'selected' : '' }}>متابعة</option>
                        <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>طارئ</option>
                    </select>
                </div>

                {{-- التاريخ والوقت --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">تاريخ ووقت الموعد <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="appointment_date"
                        class="form-control @error('appointment_date') is-invalid @enderror"
                        value="{{ old('appointment_date') }}" required>
                    @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- رسوم الكشف --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رسوم الكشف (ج.م) <span class="text-danger">*</span></label>
                    <input type="number" name="fee" id="feeInput"
                        class="form-control @error('fee') is-invalid @enderror"
                        value="{{ old('fee') }}" min="0" step="0.5" required>
                    @error('fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- ملاحظات --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-calendar-check me-1"></i> تأكيد الحجز
                </button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-fill fee from doctor's consultation_fee
document.getElementById('doctorSelect').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const fee = selected.getAttribute('data-fee');
    if (fee) document.getElementById('feeInput').value = fee;
});
</script>
@endpush
