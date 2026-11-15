@extends('layouts.app')
@section('title','إدارة المستخدمين')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المستخدمون</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-user-cog me-2 text-primary"></i>إدارة المستخدمين</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> مستخدم جديد</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>المستخدم</th><th>البريد</th><th>الدور</th><th>القسم</th><th>الحالة</th><th>الانضمام</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-{{ $user->role_color }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center fw-bold text-{{ $user->role_color }}" style="width:38px;height:38px;flex-shrink:0;font-size:.85rem">
                                    {{ mb_substr($user->name,0,1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->job_title ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><small>{{ $user->email }}</small></td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->color }} role-badge">{{ $role->label }}</span>
                            @endforeach
                            @if($user->roles->isEmpty()) <span class="text-muted small">بدون دور</span> @endif
                        </td>
                        <td><small class="text-muted">{{ $user->department ?? '-' }}</small></td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge border-0 bg-{{ $user->is_active ? 'success' : 'danger' }}" style="cursor:pointer;padding:.4em .7em">
                                    {{ $user->is_active ? 'نشط' : 'معطّل' }}
                                </button>
                            </form>
                        </td>
                        <td><small class="text-muted">{{ $user->joined_date?->format('d/m/Y') ?? '-' }}</small></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('حذف هذا المستخدم؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-users-slash fa-3x mb-3 d-block"></i>لا يوجد مستخدمون</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
