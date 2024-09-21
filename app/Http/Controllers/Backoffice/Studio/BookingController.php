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

        $booking_events = $room->bookings()->get(['start', 'end'])->map(function($event){
            return [
                'title' => 'Occupato',
                'start' => $event->start,
                'end' => $event->end,
                'borderColor' => '#b91c1c',
                'backgroundColor' => '#450a0a',
            ];
        });

        $google_events = collect([]);

        if($booking_settings->google_calendar_id){
            $google_events = Event::get(calendarId: $booking_settings->google_calendar_id)->map(function($event){
                return [
                    'title' => 'Occupato',
                    'start' => Carbon::parse($event->googleEvent->start->dateTime)->toDateTimeString(),
                    'end' => Carbon::parse($event->googleEvent->end->dateTime)->toDateTimeString(),
                    'borderColor' => '#b91c1c',
                    'backgroundColor' => '#450a0a',
                ];
            });
        }

        $buffer_events = collect([]);
        if($booking_settings->has_buffer){
            $buffer_events = $booking_events->merge($google_events)->map(function($event){
                return [
                    'title' => 'Pausa',
                    'start' => $event['end'],
                    'end' => Carbon::parse($event['end'])->addMinutes(30)->toDateTimeString(),
                    'borderColor' => '#0891b2',
                    'backgroundColor' => '#082f49',
                ];
            });
        }

        $events = $buffer_events->merge($google_events)->merge($booking_events);

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
