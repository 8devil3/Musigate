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
        $request_date = $request->date ?? now()->toDateString();

        $closing_weekdays = $room->studio->availability()
        ->where('is_open', false)
        ->pluck('weekday')
        ->map(function($wd){
            if($wd === 7) return 0;
            return $wd;
        });

        $booking_settings = $room->studio->booking_settings;

        $google_events = collect([]);

        if($booking_settings->google_calendar_id){
            $google_events = Event::get(calendarId: $booking_settings->google_calendar_id)->map(function($event){
                return [
                    'start' => Carbon::parse($event->googleEvent->start->dateTime)->toDateTimeString(),
                    'duration' => Carbon::parse($event->googleEvent->start->dateTime)->diffInHours(Carbon::parse($event->googleEvent->end->dateTime)),
                    'end' => Carbon::parse($event->googleEvent->end->dateTime)->toDateTimeString(),
                ];
            })->filter(function($event) use($request_date): bool {
                return Carbon::parse($event['start'])->timezone('Europe/Rome')->startOfDay()
                    ->equalTo(Carbon::parse($request_date)->startOfDay());
            });
        }

        //TODO: come integro le pause tra le prenotazioni?
        $merged_booking = $google_events->merge($room->bookings()->get(['start', 'duration', 'end']))->map(function($event) use($booking_settings){
            if($booking_settings->has_buffer && $booking_settings->buffer > 60){
                return [
                    'start' => Carbon::parse($event['start'])->toDateTimeString(),
                    'duration' => $event['duration'],
                    'end' => Carbon::parse($event['end'])->addMinutes($booking_settings->buffer)->toDateTimeString(),
                ];
            } else {
                return $event;
            }
        })->toArray();

        $slots = TimeSlotService::generate($room, $merged_booking, $request_date);

        return Inertia::render('Frontoffice/Booking/Create', compact('room', 'slots', 'booking_settings', 'closing_weekdays', 'request_date'));
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
