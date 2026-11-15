@extends('layouts.app')
@section('title','مستخدم جديد')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">المستخدمون</a></li>
    <li class="breadcrumb-item active">جديد</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm" style="max-width:800px;margin:auto">
    <div class="card-header bg-white fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>إضافة مستخدم جديد</div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">المسمى الوظيفي</label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}" placeholder="طبيب، أخصائي استقبال...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">القسم</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="العيادة، الاستقبال...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">تاريخ الانضمام</label>
                    <input type="date" name="joined_date" class="form-control" value="{{ old('joined_date', today()->toDateString()) }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">الأدوار <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @foreach($roles as $role)
                        <div class="col-md-4 col-6">
                            <label class="d-flex align-items-center gap-2 border rounded p-2 cursor-pointer {{ in_array($role->id, old('roles',[])) ? 'border-primary bg-primary bg-opacity-5' : '' }}" style="cursor:pointer">
                                <input class="form-check-input m-0" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ in_array($role->id, old('roles',[])) ? 'checked' : '' }}>
                                <span class="badge bg-{{ $role->color }}">{{ $role->label }}</span>
                                <small class="text-muted">{{ $role->name }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12" id="doctorFields" style="display:none">
                    <div class="border border-primary rounded p-3" style="background:#f0f7ff">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-md me-1"></i>بيانات الطبيب</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">التخصص</label>
                                <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="طب عام، أطفال...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">رقم الترخيص</label>
                                <input type="text" name="license_number" class="form-control" value="{{ old('license_number') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">رسوم الكشف (ج.م)</label>
                                <input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee', 200) }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> حفظ المستخدم</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
@php $doctorRoleId = $roles->where('name','doctor')->first()?->id ?? 0; @endphp
const drCheck = document.getElementById('role_{{ $doctorRoleId }}');
function toggleDr(){ if(drCheck) document.getElementById('doctorFields').style.display = drCheck.checked ? 'block' : 'none'; }
if(drCheck){ drCheck.addEventListener('change', toggleDr); toggleDr(); }
</script>
@endpush
