@extends('layouts.app')
@section('title','تعديل دور: ' . $role->label)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">الأدوار</a></li>
    <li class="breadcrumb-item active">{{ $role->label }}</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm" style="max-width:860px;margin:auto">
    <div class="card-header bg-white d-flex align-items-center gap-2">
        <span class="badge bg-{{ $role->color }} fs-6">{{ $role->label }}</span>
        <span class="fw-bold">تعديل الصلاحيات</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الاسم المعروض</label>
                    <input type="text" name="label" class="form-control" value="{{ old('label', $role->label) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اللون</label>
                    <select name="color" class="form-select">
                        @foreach(['primary','success','danger','warning','info','secondary','dark'] as $c)
                            <option value="{{ $c }}" {{ old('color', $role->color) === $c ? 'selected' : '' }}>
                                {{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-3">الصلاحيات</h6>
            @if($role->name === 'admin')
                <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>المدير يملك جميع الصلاحيات تلقائياً</div>
            @else
                @foreach($permissions as $group => $perms)
                <div class="card border mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
                        <span class="fw-semibold small">{{ $group }}</span>
                        <button type="button" class="btn btn-xs btn-outline-secondary btn-sm toggle-group" data-group="{{ $group }}" style="font-size:.75rem;padding:2px 8px">
                            تحديد الكل
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($perms as $perm)
                            <div class="col-md-4 col-6">
                                <label class="d-flex align-items-center gap-2 p-2 rounded border {{ in_array($perm->id, $rolePermissionIds) ? 'border-primary bg-primary bg-opacity-5' : '' }}" style="cursor:pointer;font-size:.85rem">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                        class="form-check-input m-0 perm-{{ $group }}"
                                        {{ in_array($perm->id, $rolePermissionIds) ? 'checked' : '' }}>
                                    {{ $perm->label }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> حفظ الصلاحيات</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.toggle-group').forEach(btn => {
    btn.addEventListener('click', function() {
        const group = this.dataset.group;
        const checkboxes = document.querySelectorAll('.perm-' + group);
        const allChecked = [...checkboxes].every(c => c.checked);
        checkboxes.forEach(c => c.checked = !allChecked);
    });
});
</script>
@endpush
