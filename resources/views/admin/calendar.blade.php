@extends('layouts.app')
@section('title', 'Leave Calendar')
@section('page-title', 'Leave Calendar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,listWeek'
            },
            events: @json($leaves),
            height: 'auto',
            eventDisplay: 'block',
        });
        calendar.render();
    });
</script>
@endpush
