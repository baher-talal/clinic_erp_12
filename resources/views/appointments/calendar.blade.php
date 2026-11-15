@extends('layouts.app')
@section('title', 'تقويم المواعيد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">التقويم</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<style>
#calendar .fc-toolbar-title { font-size: 1.1rem; font-weight: 700; }
#calendar .fc-event { font-size: .8rem; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>تقويم المواعيد</h5>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> موعد جديد
    </a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        direction: 'rtl',
        locale: 'ar',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: @json($appointments),
        eventClick: function(info) {
            window.location.href = info.event.url;
            info.jsEvent.preventDefault();
        },
        height: 'auto'
    });
    calendar.render();
});
</script>
@endpush
