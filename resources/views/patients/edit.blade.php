@extends('layouts.app')
@section('title', 'تعديل بيانات المريض')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">المرضى</a></li>
    <li class="breadcrumb-item"><a href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm" style="max-width:860px;margin:auto">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>تعديل بيانات المريض</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')

            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-id-card me-1"></i> البيانات الأساسية</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $patient->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الرقم القومي</label>
                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror"
                        value="{{ old('national_id', $patient->national_id) }}" maxlength="14">
                    @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">الجنس <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="male"   {{ old('gender', $patient->gender) == 'male'   ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">تاريخ الميلاد <span class="text-danger">*</span></label>
                    <input type="date" name="birth_date" class="form-control"
                        value="{{ old('birth_date', $patient->birth_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">فصيلة الدم</label>
                    <select name="blood_type" class="form-select">
                        <option value="">-- غير محدد --</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                            <option value="{{ $bt }}" {{ old('blood_type', $patient->blood_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-phone me-1"></i> بيانات التواصل</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $patient->phone) }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">هاتف للطوارئ</label>
                    <input type="tel" name="phone_emergency" class="form-control"
                        value="{{ old('phone_emergency', $patient->phone_emergency) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $patient->email) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">العنوان</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $patient->address) }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-heartbeat me-1"></i> المعلومات الطبية</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الحساسية</label>
                    <textarea name="allergies" class="form-control" rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الأمراض المزمنة</label>
                    <textarea name="chronic_diseases" class="form-control" rows="3">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fas fa-save me-1"></i> حفظ التعديلات
                </button>
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
