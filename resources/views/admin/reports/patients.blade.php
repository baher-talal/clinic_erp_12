@extends('layouts.app')
@section('title','تقارير المرضى')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تقارير المرضى</li>
@endsection
@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label small fw-semibold">من</label><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-4"><label class="form-label small fw-semibold">إلى</label><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100">عرض</button></div>
        </form>
    </div>
</div>
<div class="row g-3">
    <div class="col-sm-6"><div class="card border-0 shadow-sm text-center p-4"><div class="fs-1 fw-bold text-primary">{{ $totalPatients }}</div><div class="text-muted">إجمالي المرضى</div></div></div>
    <div class="col-sm-6"><div class="card border-0 shadow-sm text-center p-4"><div class="fs-1 fw-bold text-success">{{ $newPatients }}</div><div class="text-muted">مرضى جدد في الفترة</div></div></div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">توزيع المرضى حسب الجنس</div>
            <div class="card-body">
                @php $total = $byGender->sum(); @endphp
                @foreach(['male'=>'ذكور','female'=>'إناث'] as $g=>$l)
                @php $count = $byGender[$g] ?? 0; $pct = $total ? round($count/$total*100) : 0; @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1"><span>{{ $l }}</span><span class="fw-bold">{{ $count }} ({{ $pct }}%)</span></div>
                    <div class="progress" style="height:10px"><div class="progress-bar bg-{{ $g==='male'?'primary':'danger' }}" style="width:{{ $pct }}%"></div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">توزيع فصائل الدم</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>فصيلة الدم</th><th class="text-center">عدد المرضى</th></tr></thead>
                    <tbody>
                        @foreach($byBloodType->sortDesc() as $type => $count)
                        <tr><td><span class="badge bg-danger">{{ $type }}</span></td><td class="text-center fw-bold">{{ $count }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
