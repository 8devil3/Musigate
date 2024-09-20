<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room\Room;
use App\Services\GoogleCalendarAPIService;
use App\Services\TimeSlotService;
use Carbon\Carbon;
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
            $wd++;
            if($wd === 7) return $wd = 0;
        });

        if(!empty($request->toArray())){
            session()->put('request', $request->toArray());
            $request = session('request');
        }

        $availability = $room->studio->availability()->get(['weekday', 'start', 'end', 'is_open'])->toArray();

        $booking_settings = $room->studio->booking_settings;

        $bookings = $room->bookings()->get(['start', 'end']);

        $google_events = collect([]);

        if($booking_settings->google_calendar_id){
            $google_events = Event::get(calendarId: $booking_settings->google_calendar_id)->map(function($event){
                return [
                    'start' => Carbon::parse($event->googleEvent->start->dateTime)->toDateTimeString(),
                    'end' => Carbon::parse($event->googleEvent->end->dateTime)->toDateTimeString(),
                ];
            });
        }

        $events = $google_events->merge($bookings)->map(function($event){
            return [
                'title' => 'Occupato',
                'start' => $event->start,
                'end' => $event->end,
            ];
        });

        return Inertia::render('Frontoffice/Booking/Create', compact('events', 'room', 'availability', 'booking_settings', 'closing_weekdays', 'request'));
    }

    public function store(Request $request): RedirectResponse
    {
        dd(Carbon::parse($request->start)->toDateTimeString(), Carbon::parse($request->end)->toDateTimeString(), $request->duration);

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
