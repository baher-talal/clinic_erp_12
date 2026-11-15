@extends('layouts.app')
@section('title', 'المواعيد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المواعيد</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>إدارة المواعيد</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.calendar') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-calendar-alt me-1"></i> التقويم
        </a>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> موعد جديد
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">التاريخ</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">الحالة</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- الكل --</option>
                    <option value="pending"     {{ request('status')=='pending'     ? 'selected':'' }}>قيد الانتظار</option>
                    <option value="confirmed"   {{ request('status')=='confirmed'   ? 'selected':'' }}>مؤكد</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>جارٍ</option>
                    <option value="completed"   {{ request('status')=='completed'   ? 'selected':'' }}>مكتمل</option>
                    <option value="cancelled"   {{ request('status')=='cancelled'   ? 'selected':'' }}>ملغي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">الطبيب</label>
                <select name="doctor_id" class="form-select form-select-sm">
                    <option value="">-- الكل --</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id')==$doc->id ? 'selected':'' }}>
                            د. {{ $doc->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">بحث</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm">مسح</a>
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
                        <th>المريض</th>
                        <th>الطبيب</th>
                        <th>التاريخ والوقت</th>
                        <th>النوع</th>
                        <th>الرسوم</th>
                        <th>الحالة</th>
                        <th>الدفع</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appt)
                    <tr>
                        <td><small class="text-muted">{{ $appt->appointment_code }}</small></td>
                        <td class="fw-semibold">{{ $appt->patient->name }}</td>
                        <td>د. {{ $appt->doctor->user->name }}</td>
                        <td>
                            <div>{{ $appt->appointment_date->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $appt->appointment_date->format('h:i A') }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $appt->type_label }}</span></td>
                        <td>{{ number_format($appt->fee, 2) }} ج.م</td>
                        <td><span class="badge bg-{{ $appt->status_color }}">{{ $appt->status_label }}</span></td>
                        <td>
                            @if($appt->payment_status === 'paid')
                                <span class="badge bg-success">مدفوع</span>
                            @else
                                <span class="badge bg-danger">غير مدفوع</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>لا توجد مواعيد
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $appointments->links() }}</div>
    </div>
</div>
@endsection
