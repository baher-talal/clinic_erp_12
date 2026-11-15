@extends('layouts.app')
@section('title', 'تفاصيل الموعد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">المواعيد</a></li>
    <li class="breadcrumb-item active">{{ $appointment->appointment_code }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        {{-- بيانات الموعد --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2 text-primary"></i>بيانات الموعد</h6>
                <span class="badge bg-{{ $appointment->status_color }} fs-6">{{ $appointment->status_label }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">كود الموعد</small>
                        <strong>{{ $appointment->appointment_code }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">نوع الكشف</small>
                        <strong>{{ $appointment->type_label }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">التاريخ والوقت</small>
                        <strong>{{ $appointment->appointment_date->format('d/m/Y - h:i A') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">الرسوم</small>
                        <strong class="text-success fs-5">{{ number_format($appointment->fee, 2) }} ج.م</strong>
                        @if($appointment->payment_status === 'paid')
                            <span class="badge bg-success ms-2">مدفوع</span>
                        @else
                            <span class="badge bg-danger ms-2">غير مدفوع</span>
                        @endif
                    </div>
                    @if($appointment->notes)
                    <div class="col-12">
                        <small class="text-muted d-block">ملاحظات</small>
                        <p class="mb-0">{{ $appointment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- السجل الطبي --}}
        @if($appointment->medicalRecord)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-medical me-2 text-success"></i>السجل الطبي</h6>
            </div>
            <div class="card-body">
                @php $record = $appointment->medicalRecord @endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted fw-semibold d-block">الشكوى الرئيسية</small>
                        <p>{{ $record->complaint }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted fw-semibold d-block">التشخيص</small>
                        <p>{{ $record->diagnosis }}</p>
                    </div>
                    @if($record->weight || $record->blood_pressure)
                    <div class="col-12">
                        <small class="text-muted fw-semibold d-block mb-2">القياسات</small>
                        <div class="d-flex flex-wrap gap-3">
                            @if($record->weight)   <span class="badge bg-light text-dark border">الوزن: {{ $record->weight }} كجم</span> @endif
                            @if($record->height)   <span class="badge bg-light text-dark border">الطول: {{ $record->height }} سم</span> @endif
                            @if($record->blood_pressure) <span class="badge bg-light text-dark border">ضغط الدم: {{ $record->blood_pressure }}</span> @endif
                            @if($record->temperature)    <span class="badge bg-light text-dark border">الحرارة: {{ $record->temperature }}°</span> @endif
                            @if($record->pulse)          <span class="badge bg-light text-dark border">النبض: {{ $record->pulse }}</span> @endif
                        </div>
                    </div>
                    @endif
                </div>
                <a href="{{ route('records.show', $record) }}" class="btn btn-sm btn-outline-success mt-3">
                    <i class="fas fa-eye me-1"></i> عرض السجل الكامل
                </a>
            </div>
        </div>
        @else
        @if($appointment->status !== 'cancelled')
        <div class="card border-0 shadow-sm mb-3 border-dashed">
            <div class="card-body text-center py-4">
                <i class="fas fa-file-medical fa-3x text-muted mb-3 d-block"></i>
                <p class="text-muted mb-3">لم يتم إنشاء سجل طبي لهذا الموعد بعد</p>
                <a href="{{ route('records.create', $appointment) }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> إنشاء سجل طبي وروشتة
                </a>
            </div>
        </div>
        @endif
        @endif
    </div>

    {{-- الإجراءات --}}
    <div class="col-lg-4">
        {{-- بيانات المريض --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-user me-2 text-primary"></i>المريض
            </div>
            <div class="card-body">
                <div class="fw-bold">{{ $appointment->patient->name }}</div>
                <small class="text-muted">{{ $appointment->patient->patient_code }}</small>
                <div class="mt-2">
                    <i class="fas fa-phone me-1 text-muted"></i>
                    <a href="tel:{{ $appointment->patient->phone }}">{{ $appointment->patient->phone }}</a>
                </div>
                <a href="{{ route('patients.show', $appointment->patient) }}" class="btn btn-sm btn-outline-primary mt-2">
                    عرض الملف الكامل
                </a>
            </div>
        </div>

        {{-- بيانات الطبيب --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-user-md me-2 text-info"></i>الطبيب
            </div>
            <div class="card-body">
                <div class="fw-bold">د. {{ $appointment->doctor->user->name }}</div>
                <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
            </div>
        </div>

        {{-- تغيير الحالة --}}
        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-cog me-2"></i>تحديث الحالة
            </div>
            <div class="card-body d-flex flex-column gap-2">
                @if($appointment->status === 'pending')
                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button class="btn btn-info w-100 btn-sm">
                        <i class="fas fa-check me-1"></i> تأكيد الموعد
                    </button>
                </form>
                @endif
                @if(in_array($appointment->status, ['pending','confirmed']))
                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button class="btn btn-primary w-100 btn-sm">
                        <i class="fas fa-play me-1"></i> بدء الكشف
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button class="btn btn-outline-danger w-100 btn-sm"
                        onclick="return confirm('هل أنت متأكد من إلغاء الموعد؟')">
                        <i class="fas fa-times me-1"></i> إلغاء الموعد
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- الدفع --}}
        @if($appointment->payment_status !== 'paid' && $appointment->status !== 'cancelled')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-cash-register me-2 text-success"></i>تسجيل الدفع
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('appointments.pay', $appointment) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">طريقة الدفع</label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="cash">كاش</option>
                            <option value="card">بطاقة</option>
                            <option value="transfer">تحويل بنكي</option>
                        </select>
                    </div>
                    <button class="btn btn-success w-100 btn-sm">
                        <i class="fas fa-check me-1"></i> تأكيد الدفع ({{ number_format($appointment->fee, 2) }} ج.م)
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
