@extends('layouts.app')
@section('title','تعديل مستخدم')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">المستخدمون</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm" style="max-width:800px;margin:auto">
    <div class="card-header bg-white fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>تعديل: {{ $user->name }}</div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">البريد <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">كلمة مرور جديدة <small class="text-muted">(اتركه فارغاً للإبقاء)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">المسمى الوظيفي</label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $user->job_title) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">القسم</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $user->is_active ? 'selected':'' }}>نشط</option>
                        <option value="0" {{ !$user->is_active ? 'selected':'' }}>معطّل</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">الأدوار <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @foreach($roles as $role)
                        <div class="col-md-4 col-6">
                            <label class="d-flex align-items-center gap-2 border rounded p-2 {{ in_array($role->id, $userRoleIds) ? 'border-primary bg-primary bg-opacity-5' : '' }}" style="cursor:pointer">
                                <input class="form-check-input m-0" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }}>
                                <span class="badge bg-{{ $role->color }}">{{ $role->label }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-1"></i> حفظ التعديلات</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
