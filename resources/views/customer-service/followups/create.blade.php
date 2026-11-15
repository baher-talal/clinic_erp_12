@extends('layouts.app')
@section('title','متابعة جديدة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cs.followups.index') }}">المتابعات</a></li>
    <li class="breadcrumb-item active">جديدة</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm" style="max-width:720px;margin:auto">
    <div class="card-header bg-white fw-bold"><i class="fas fa-tasks me-2 text-warning"></i>إنشاء متابعة جديدة</div>
    <div class="card-body">
        <form action="{{ route('cs.followups.store') }}" method="POST">
            @csrf
            @if($callId)<input type="hidden" name="call_id" value="{{ $callId }}">@endif
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">عنوان المتابعة <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="مثل: تذكير المريض بموعده غداً" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">المريض</label>
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
                    <label class="form-label fw-semibold">المسؤول عن المتابعة <span class="text-danger">*</span></label>
                    <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" required>
                        <option value="">-- اختر --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('assigned_to', auth()->id()) == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">نوع المتابعة <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- اختر --</option>
                        <option value="appointment_reminder" {{ old('type')==='appointment_reminder' ? 'selected':'' }}>تذكير بموعد</option>
                        <option value="post_treatment"       {{ old('type')==='post_treatment'       ? 'selected':'' }}>متابعة بعد العلاج</option>
                        <option value="complaint"            {{ old('type')==='complaint'            ? 'selected':'' }}>شكوى</option>
                        <option value="lab_results"          {{ old('type')==='lab_results'          ? 'selected':'' }}>نتائج مختبر</option>
                        <option value="other"                {{ old('type')==='other'                ? 'selected':'' }}>أخرى</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">الأولوية <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="low"    {{ old('priority')==='low'    ? 'selected':'' }}>منخفض</option>
                        <option value="medium" {{ old('priority','medium')==='medium' ? 'selected':'' }}>متوسط</option>
                        <option value="high"   {{ old('priority')==='high'   ? 'selected':'' }}>عالي</option>
                        <option value="urgent" {{ old('priority')==='urgent' ? 'selected':'' }}>عاجل</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">موعد الإغلاق <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', now()->addDay()->format('Y-m-d\TH:i')) }}" required>
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">الوصف</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="تفاصيل إضافية عن المتابعة...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-1"></i> حفظ المتابعة</button>
                <a href="{{ route('cs.followups.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
