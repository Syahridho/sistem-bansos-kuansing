<x-app-layout>
    <x-slot name="header">Kalender Jadwal Bantuan</x-slot>

    <div class="max-w-7xl mx-auto">
        @include('calender.partials.calendar-widget', ['eventsUrl' => $eventsUrl, 'calendarLegend' => $calendarLegend])
    </div>
</x-app-layout>
