@extends('layouts.app')
@section('title','تقارير المواعيد')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تقارير المواعيد</li>
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

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div><small class="text-muted">إجمالي المواعيد</small></div></div>
    <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-success">{{ $stats['completed'] }}</div><small class="text-muted">مكتملة</small></div></div>
    <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-danger">{{ $stats['cancelled'] }}</div><small class="text-muted">ملغية</small></div></div>
    <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-warning">{{ number_format($stats['revenue'],0) }}</div><small class="text-muted">الإيرادات (ج.م)</small></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">توزيع المواعيد على الأطباء</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>الطبيب</th><th>التخصص</th><th class="text-center">المواعيد</th></tr></thead>
                    <tbody>
                        @foreach($byDoctor as $doctor)
                        <tr>
                            <td>د. {{ $doctor->user->name }}</td>
                            <td><small class="text-muted">{{ $doctor->specialization }}</small></td>
                            <td class="text-center"><span class="badge bg-primary">{{ $doctor->count }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">المواعيد اليومية</div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:350px;overflow-y:auto">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>التاريخ</th><th class="text-center">عدد</th><th class="text-end">إيرادات</th></tr></thead>
                        <tbody>
                            @foreach($daily as $day)
                            <tr>
                                <td><small>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</small></td>
                                <td class="text-center"><span class="badge bg-info">{{ $day->count }}</span></td>
                                <td class="text-end text-success small">{{ number_format($day->revenue,0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
