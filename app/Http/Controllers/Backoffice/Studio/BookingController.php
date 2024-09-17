<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room\Room;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Google_Service_Calendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\GoogleCalendar\Event;

class BookingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('', compact(''));
    }

    public function create(Request $request, Room $room): Response
    {
        $closing_weekdays = $room->studio->availability()
        ->where('is_open', false)
        ->pluck('weekday')
        ->map(function($wd){
            if($wd === 7) return 0;
            return $wd;
        });

        $client = new \Google_Client();
        $client->setAccessToken(auth()->user()->google_token);

        // Crea un servizio di Google Calendar
        $service = new Google_Service_Calendar($client);

        // Ottieni la lista dei calendari dell'utente
        $calendar_list = $service->calendarList->listCalendarList();

        // dd($calendar_list[2]->getId());

        $google_events = Event::get(calendarId: $calendar_list[2]->getId());

        dd($google_events->toArray());

        $request_date = $request->date ?? now()->toDateString();

        $current_day_availability = $room->studio->availability()->where('weekday', Carbon::parse($request_date)->isoWeekday())->first();

        $slots = TimeSlotService::generate($current_day_availability, $room->bookings, $request_date);

        return Inertia::render('Frontoffice/Booking/Create', compact('room', 'slots', 'closing_weekdays', 'request_date'));
    }

    public function store(Request $request): RedirectResponse
    {
        dd(Carbon::parse($request->date)->toDateTimeString());       

        return to_route('');
    }

    public function show(Booking $booking): Response
    {
        return Inertia::render('', compact(''));
    }

    public function edit(Booking $booking): Response
    {
        return Inertia::render('', compact(''));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        return to_route('', $booking->id);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return back()->with('success', 'Prenotazione eliminata');
    }
}
