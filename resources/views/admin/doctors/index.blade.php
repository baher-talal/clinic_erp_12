@extends('layouts.app')
@section('title','إدارة الأطباء')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الأطباء</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-user-md me-2 text-primary"></i>إدارة الأطباء</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> إضافة طبيب</a>
</div>

<div class="row g-3">
    @forelse($doctors as $doctor)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary" style="width:50px;height:50px;font-size:1.1rem;flex-shrink:0">
                        {{ mb_substr($doctor->user->name,0,1) }}
                    </div>
                    <div>
                        <div class="fw-bold">د. {{ $doctor->user->name }}</div>
                        <span class="badge bg-info">{{ $doctor->specialization }}</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-{{ $doctor->is_available ? 'success':'secondary' }}">
                            {{ $doctor->is_available ? 'متاح' : 'غير متاح' }}
                        </span>
                    </div>
                </div>
                <div class="row g-2 text-center mb-3">
                    <div class="col-4 border-end">
                        <div class="fw-bold text-primary">{{ $doctor->appointments_count }}</div>
                        <small class="text-muted" style="font-size:.7rem">إجمالي المواعيد</small>
                    </div>
                    <div class="col-4 border-end">
                        <div class="fw-bold text-warning">{{ $doctor->today_count }}</div>
                        <small class="text-muted" style="font-size:.7rem">مواعيد اليوم</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-success">{{ number_format($doctor->consultation_fee,0) }}</div>
                        <small class="text-muted" style="font-size:.7rem">رسوم الكشف</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary flex-grow-1">عرض الملف</a>
                    <a href="{{ route('admin.users.edit', $doctor->user) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body text-center py-5 text-muted"><i class="fas fa-user-md fa-3x mb-3 d-block"></i>لا يوجد أطباء مسجلون</div></div></div>
    @endforelse
</div>
<div class="mt-3">{{ $doctors->links() }}</div>
@endsection
