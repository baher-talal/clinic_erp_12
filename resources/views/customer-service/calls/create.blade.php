@extends('layouts.app')
@section('title','تسجيل مكالمة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.calls.index') }}">المكالمات</a></li>
    <li class="breadcrumb-item active">تسجيل مكالمة</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm" style="max-width:720px;margin:auto">
    <div class="card-header bg-white fw-bold"><i class="fas fa-phone-alt me-2 text-primary"></i>تسجيل مكالمة جديدة</div>
    <div class="card-body">
        <form action="{{ route('cs.calls.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">نوع المكالمة</label>
                    <div class="d-flex gap-3">
                        <label class="d-flex align-items-center gap-2 border rounded p-2 flex-grow-1" style="cursor:pointer">
                            <input type="radio" name="type" value="incoming" {{ old('type','incoming') === 'incoming' ? 'checked' : '' }} class="form-check-input m-0">
                            <i class="fas fa-phone-alt text-success"></i> واردة
                        </label>
                        <label class="d-flex align-items-center gap-2 border rounded p-2 flex-grow-1" style="cursor:pointer">
                            <input type="radio" name="type" value="outgoing" {{ old('type') === 'outgoing' ? 'checked' : '' }} class="form-check-input m-0">
                            <i class="fas fa-phone text-primary"></i> صادرة
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">التاريخ والوقت <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="called_at" class="form-control" value="{{ old('called_at', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                    <input type="tel" name="caller_phone" class="form-control @error('caller_phone') is-invalid @enderror" value="{{ old('caller_phone') }}" placeholder="01XXXXXXXXX" required>
                    @error('caller_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اسم المتصل (إن لم يكن مريضاً)</label>
                    <input type="text" name="caller_name" class="form-control" value="{{ old('caller_name') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">المريض (إن كان مسجلاً)</label>
                    <select name="patient_id" class="form-select">
                        <option value="">-- بحث عن مريض --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->patient_code }} - {{ $p->name }} ({{ $p->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">غرض المكالمة <span class="text-danger">*</span></label>
                    <select name="purpose" class="form-select @error('purpose') is-invalid @enderror" required>
                        <option value="">-- اختر --</option>
                        <option value="appointment" {{ old('purpose') === 'appointment' ? 'selected' : '' }}>حجز موعد</option>
                        <option value="inquiry"     {{ old('purpose') === 'inquiry'     ? 'selected' : '' }}>استفسار</option>
                        <option value="complaint"   {{ old('purpose') === 'complaint'   ? 'selected' : '' }}>شكوى</option>
                        <option value="followup"    {{ old('purpose') === 'followup'    ? 'selected' : '' }}>متابعة</option>
                        <option value="other"       {{ old('purpose') === 'other'       ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">النتيجة <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="answered"        {{ old('status','answered') === 'answered'        ? 'selected' : '' }}>تم الرد</option>
                        <option value="missed"          {{ old('status') === 'missed'          ? 'selected' : '' }}>فائتة</option>
                        <option value="callback_needed" {{ old('status') === 'callback_needed' ? 'selected' : '' }}>تحتاج رد</option>
                        <option value="resolved"        {{ old('status') === 'resolved'        ? 'selected' : '' }}>محلولة</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">مدة المكالمة (ثانية)</label>
                    <input type="number" name="duration_seconds" class="form-control" value="{{ old('duration_seconds') }}" min="0" placeholder="مثل: 180 = 3 دقائق">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="ملخص المكالمة...">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> حفظ المكالمة</button>
                <a href="{{ route('cs.calls.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
