<?php

namespace App\Http\Controllers;

use App\Services\CalendarEventService;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    public function __construct(
        private readonly CalendarEventService $calendarEventService
    ) {}

    public function index()
    {
        return view('calender.index', [
            'calendarLegend' => $this->calendarEventService->buildLegend(),
            'eventsUrl' => route('calender.events'),
        ]);
    }

    public function events(): JsonResponse
    {
        return response()->json($this->calendarEventService->formatEvents());
    }
}
