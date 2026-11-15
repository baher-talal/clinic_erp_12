@extends('layouts.app')
@section('title','مرضاي')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">لوحتي</a></li>
    <li class="breadcrumb-item active">مرضاي</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>مرضاي</h5>
</div>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو الهاتف..." value="{{ request('search') }}">
            <button class="btn btn-primary">بحث</button>
            @if(request('search'))<a href="{{ route('doctor.patients') }}" class="btn btn-outline-secondary">مسح</a>@endif
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>الكود</th><th>الاسم</th><th>الجنس</th><th>العمر</th><th>الهاتف</th><th>فصيلة الدم</th><th></th></tr></thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $patient->patient_code }}</span></td>
                        <td class="fw-semibold">{{ $patient->name }}</td>
                        <td><i class="fas fa-{{ $patient->gender==='male'?'mars text-primary':'venus text-danger' }}"></i> {{ $patient->gender_label }}</td>
                        <td>{{ $patient->age }}</td>
                        <td><small>{{ $patient->phone }}</small></td>
                        <td>@if($patient->blood_type)<span class="badge bg-danger">{{ $patient->blood_type }}</span>@else<span class="text-muted">-</span>@endif</td>
                        <td>
                            <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">لا يوجد مرضى</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $patients->links() }}</div>
    </div>
</div>
@endsection
