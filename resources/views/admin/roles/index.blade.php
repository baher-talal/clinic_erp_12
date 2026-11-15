@extends('layouts.app')
@section('title','الأدوار والصلاحيات')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الأدوار والصلاحيات</li>
@endsection
@section('content')
<div class="mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-shield-alt me-2 text-primary"></i>إدارة الأدوار والصلاحيات</h5>
</div>

<div class="row g-3">
    @foreach($roles as $role)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-{{ $role->color }} bg-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $role->color }} fs-6">{{ $role->label }}</span>
                    <small class="text-muted">{{ $role->name }}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary">{{ $role->users_count }} مستخدم</span>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-{{ $role->color }}">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($role->permissions->isEmpty())
                    <p class="text-muted small mb-0">لا توجد صلاحيات محددة (وصول كامل للمدير)</p>
                @else
                    @foreach($role->permissions->groupBy('group') as $group => $perms)
                    <div class="mb-2">
                        <small class="text-muted fw-semibold d-block mb-1">{{ $group }}</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($perms as $perm)
                                <span class="badge bg-light text-dark border" style="font-size:.7rem">{{ $perm->label }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
