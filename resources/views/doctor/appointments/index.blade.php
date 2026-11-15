@extends('layouts.app')
@section('title','مواعيدي')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">لوحتي</a></li>
    <li class="breadcrumb-item active">مواعيدي</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>مواعيدي</h5>
</div>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}"></div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- الكل --</option>
                    @foreach(['pending'=>'قيد الانتظار','confirmed'=>'مؤكد','in_progress'=>'جارٍ','completed'=>'مكتمل','cancelled'=>'ملغي'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('status')===$v ? 'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">بحث</button>
                <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary btn-sm">مسح</a>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>الكود</th><th>المريض</th><th>التاريخ</th><th>النوع</th><th>الحالة</th><th></th></tr></thead>
                <tbody>
                    @forelse($appointments as $appt)
                    <tr>
                        <td><small class="text-muted">{{ $appt->appointment_code }}</small></td>
                        <td class="fw-semibold">{{ $appt->patient->name }}</td>
                        <td><small>{{ $appt->appointment_date->format('d/m/Y h:i A') }}</small></td>
                        <td><span class="badge bg-secondary role-badge">{{ $appt->type_label }}</span></td>
                        <td><span class="badge bg-{{ $appt->status_color }} role-badge">{{ $appt->status_label }}</span></td>
                        <td class="d-flex gap-1">
                            @if(in_array($appt->status,['pending','confirmed','in_progress']) && !$appt->medicalRecord)
                            <a href="{{ route('records.create', $appt) }}" class="btn btn-sm btn-success" title="كشف"><i class="fas fa-stethoscope"></i></a>
                            @endif
                            <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">لا توجد مواعيد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $appointments->links() }}</div>
    </div>
</div>
@endsection
