@extends('layouts.app')
@section('title', 'المرضى')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المرضى</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>إدارة المرضى</h5>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> إضافة مريض
    </a>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الكود أو رقم الهاتف..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary">بحث</button>
                @if(request('search'))
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">مسح</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الكود</th>
                        <th>الاسم</th>
                        <th>الجنس</th>
                        <th>السن</th>
                        <th>الهاتف</th>
                        <th>فصيلة الدم</th>
                        <th>المواعيد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $patient->patient_code }}</span></td>
                        <td class="fw-semibold">{{ $patient->name }}</td>
                        <td>
                            <i class="fas fa-{{ $patient->gender === 'male' ? 'mars text-primary' : 'venus text-danger' }}"></i>
                            {{ $patient->gender_label }}
                        </td>
                        <td>{{ $patient->age }} سنة</td>
                        <td><a href="tel:{{ $patient->phone }}">{{ $patient->phone }}</a></td>
                        <td>
                            @if($patient->blood_type)
                                <span class="badge bg-danger">{{ $patient->blood_type }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $patient->appointments_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="btn btn-outline-success" title="موعد جديد">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3 d-block"></i>
                            لا يوجد مرضى مسجلون
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection
