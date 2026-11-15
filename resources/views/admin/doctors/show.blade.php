@extends('layouts.app')
@section('title','ملف الطبيب')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">الأطباء</a></li>
    <li class="breadcrumb-item active">د. {{ $doctor->user->name }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem">
                    {{ mb_substr($doctor->user->name,0,1) }}
                </div>
                <h5 class="fw-bold">د. {{ $doctor->user->name }}</h5>
                <span class="badge bg-info mb-2">{{ $doctor->specialization }}</span>
                <div class="text-muted small mb-3">{{ $doctor->user->email }}</div>
                <div class="d-flex justify-content-around text-center">
                    <div><div class="fw-bold fs-4 text-primary">{{ $appointments->total() }}</div><small class="text-muted">المواعيد</small></div>
                    <div><div class="fw-bold fs-4 text-success">{{ number_format($totalRevenue,0) }}</div><small class="text-muted">الإيرادات</small></div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold small">تعديل بيانات الطبيب</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">التخصص</label>
                        <input type="text" name="specialization" class="form-control form-control-sm" value="{{ $doctor->specialization }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">رسوم الكشف</label>
                        <input type="number" name="consultation_fee" class="form-control form-control-sm" value="{{ $doctor->consultation_fee }}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="d-flex align-items-center gap-2">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" {{ $doctor->is_available ? 'checked':'' }}>
                            <span class="small fw-semibold">متاح للمواعيد</span>
                        </label>
                    </div>
                    <button class="btn btn-primary btn-sm w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">آخر المواعيد</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>المريض</th><th>التاريخ</th><th>النوع</th><th>الحالة</th><th>الرسوم</th></tr></thead>
                        <tbody>
                            @forelse($appointments as $appt)
                            <tr>
                                <td class="fw-semibold small">{{ $appt->patient->name }}</td>
                                <td><small>{{ $appt->appointment_date->format('d/m/Y h:i A') }}</small></td>
                                <td><span class="badge bg-secondary role-badge">{{ $appt->type_label }}</span></td>
                                <td><span class="badge bg-{{ $appt->status_color }} role-badge">{{ $appt->status_label }}</span></td>
                                <td><small class="text-success">{{ number_format($appt->fee,0) }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">لا توجد مواعيد</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $appointments->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
