@php
    $eventsUrl = $eventsUrl ?? route('calender.events');
    $calendarLegend = $calendarLegend ?? [];
@endphp

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<style>
    #jadwal-bantuan-calendar .fc {
        --fc-border-color: #e2e8f0;
        --fc-button-bg-color: #0f172a;
        --fc-button-border-color: #0f172a;
        --fc-button-hover-bg-color: #1e293b;
        --fc-button-hover-border-color: #1e293b;
        --fc-button-active-bg-color: #334155;
        --fc-button-active-border-color: #334155;
        --fc-today-bg-color: #f8fafc;
        font-family: 'Inter', sans-serif;
    }
    #jadwal-bantuan-calendar .fc-toolbar-title {
        font-size: clamp(0.95rem, 2.5vw, 1.15rem);
        font-weight: 600;
        color: #0f172a;
    }
    #jadwal-bantuan-calendar .fc-col-header-cell-cushion,
    #jadwal-bantuan-calendar .fc-daygrid-day-number {
        color: #475569;
        font-size: 0.75rem;
    }
    #jadwal-bantuan-calendar .fc-event {
        border-radius: 4px;
        font-size: 0.7rem;
        cursor: pointer;
    }
    @media (max-width: 639px) {
        #jadwal-bantuan-calendar .fc-toolbar {
            flex-direction: column;
            gap: 0.5rem;
        }
        #jadwal-bantuan-calendar .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        #jadwal-bantuan-calendar .fc-button {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }
        #jadwal-bantuan-calendar .fc-event-title {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
    @media (min-width: 640px) and (max-width: 1023px) {
        #jadwal-bantuan-calendar .fc-event { font-size: 0.72rem; }
    }
</style>

<div x-data="{
        modalOpen: false,
        selected: null,
        openDetail(event) {
            this.selected = {
                title: event.title,
                color: event.backgroundColor,
                ...event.extendedProps
            };
            this.modalOpen = true;
        }
     }">

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 px-4 sm:px-6 py-4 sm:py-5">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold tracking-tight text-slate-900">Monitoring Jadwal Bantuan</h3>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">Rentang waktu penyaluran per periode. Klik jadwal untuk melihat detail.</p>
                </div>
                <span class="inline-flex items-center self-start rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 shrink-0">Read-only</span>
            </div>

            @if(!empty($calendarLegend))
                <div class="mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                    @foreach($calendarLegend as $item)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2 py-1 text-[11px] sm:text-xs text-slate-600 max-w-full">
                            <span class="h-2 w-2 rounded-full shrink-0" style="background-color: {{ $item['color'] }}"></span>
                            <span class="truncate">{{ $item['name'] }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="p-2 sm:p-4 md:p-6 overflow-x-auto pb-4">
            <div id="jadwal-bantuan-calendar" class="min-w-[800px] min-h-[480px] sm:min-h-[480px] lg:min-h-[560px]"></div>
        </div>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>
        <div x-show="modalOpen" x-transition @click.stop
             class="relative w-full sm:max-w-md rounded-t-2xl sm:rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xl max-h-[85vh] overflow-y-auto">
            <button type="button" @click="modalOpen = false" class="absolute right-4 top-4 rounded-md p-1 text-slate-400 hover:bg-slate-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
            <template x-if="selected">
                <div>
                    <div class="mb-4 flex items-start gap-3 pr-8">
                        <span class="mt-1.5 h-3 w-3 shrink-0 rounded-full" :style="'background-color:' + selected.color"></span>
                        <div class="min-w-0">
                            <h4 class="text-base sm:text-lg font-semibold text-slate-900 break-words" x-text="selected.title"></h4>
                            <p class="mt-1 text-sm text-slate-500 break-words" x-text="selected.jenis_bantuan"></p>
                        </div>
                    </div>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                            <dt class="text-slate-500 shrink-0">Tanggal Mulai</dt>
                            <dd class="font-medium text-slate-900 text-right" x-text="selected.tanggal_mulai"></dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                            <dt class="text-slate-500 shrink-0">Tanggal Akhir</dt>
                            <dd class="font-medium text-slate-900 text-right" x-text="selected.tanggal_akhir"></dd>
                        </div>
                        <div class="flex justify-between gap-4 items-center">
                            <dt class="text-slate-500">Status</dt>
                            <dd>
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                      :class="selected.status === 'buka' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700'"
                                      x-text="selected.status_label"></span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </template>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/id.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('jadwal-bantuan-calendar');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    function isMobile() { return window.innerWidth < 640; }
    function isTablet() { return window.innerWidth >= 640 && window.innerWidth < 1024; }

    function headerToolbar() {
        if (isMobile()) {
            return { left: 'prev,next', center: 'title', right: 'today' };
        }
        if (isTablet()) {
            return { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' };
        }
        return { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' };
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'id',
        initialView: 'dayGridMonth',
        headerToolbar: headerToolbar(),
        height: 'auto',
        contentHeight: isMobile() ? 'auto' : undefined,
        editable: false,
        selectable: false,
        eventStartEditable: false,
        eventDurationEditable: false,
        eventResizableFromStart: false,
        droppable: false,
        displayEventTime: false,
        dayMaxEvents: isMobile() ? 2 : 4,
        moreLinkClick: 'popover',
        events: @json($eventsUrl),
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const root = calendarEl.closest('[x-data]');
            if (root && root._x_dataStack && root._x_dataStack[0]) {
                root._x_dataStack[0].openDetail(info.event);
            }
        },
        eventDidMount: function (info) {
            const p = info.event.extendedProps;
            info.el.title = p.jenis_bantuan + ' | ' + p.tanggal_mulai + ' s/d ' + p.tanggal_akhir;
        },
    });

    calendar.render();

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            calendar.setOption('headerToolbar', headerToolbar());
            calendar.setOption('dayMaxEvents', isMobile() ? 2 : 4);
        }, 200);
    });
});
</script>
