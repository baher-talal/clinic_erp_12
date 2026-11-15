@extends('layouts.app')
@section('title', 'إضافة مريض جديد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">المرضى</a></li>
    <li class="breadcrumb-item active">مريض جديد</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm" style="max-width:860px;margin:auto">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>إضافة مريض جديد</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <h6 class="text-primary fw-bold mb-3 mt-1">
                <i class="fas fa-id-card me-1"></i> البيانات الأساسية
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="الاسم الرباعي" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الرقم القومي</label>
                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror"
                        value="{{ old('national_id') }}" placeholder="14 رقم" maxlength="14">
                    @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">الجنس <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="">-- اختر --</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">تاريخ الميلاد <span class="text-danger">*</span></label>
                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                        value="{{ old('birth_date') }}" required>
                    @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">فصيلة الدم</label>
                    <select name="blood_type" class="form-select">
                        <option value="">-- غير محدد --</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                            <option value="{{ $bt }}" {{ old('blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="text-primary fw-bold mb-3">
                <i class="fas fa-phone me-1"></i> بيانات التواصل
            </h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">هاتف للطوارئ</label>
                    <input type="tel" name="phone_emergency" class="form-control"
                        value="{{ old('phone_emergency') }}" placeholder="01XXXXXXXXX">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email') }}" placeholder="example@email.com">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">العنوان</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="المحافظة، المدينة، الشارع...">{{ old('address') }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="text-primary fw-bold mb-3">
                <i class="fas fa-heartbeat me-1"></i> المعلومات الطبية
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الحساسية (إن وجدت)</label>
                    <textarea name="allergies" class="form-control" rows="3"
                        placeholder="اذكر أي حساسية من أدوية أو مواد معينة">{{ old('allergies') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الأمراض المزمنة (إن وجدت)</label>
                    <textarea name="chronic_diseases" class="form-control" rows="3"
                        placeholder="السكري، ضغط الدم، الربو...">{{ old('chronic_diseases') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> حفظ المريض
                </button>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
