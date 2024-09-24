<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room\Room;
use App\Services\GoogleCalendarAPIService;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\GoogleCalendar\Event;

class BookingController extends Controller
{
    public function index(Request $request): Response
    {
        $booking_settings = auth()->user()->studio->booking_settings;
        $availability = auth()->user()->studio->availability;
        $time_fraction = $booking_settings->allow_fractional_durations || $booking_settings->has_buffer ? '30 minutes' : '1 hour';

        $current_room_id = request('room_id', 1000);
        $rooms = auth()->user()->studio->rooms()->pluck('name', 'id')->toArray();

        $bookings = Booking::with(['room:id,name,color', 'user:id,first_name,last_name'])
            ->when($current_room_id !== 1000, function($query) use($current_room_id){
                $query->where('room_id', $current_room_id);
            })
            ->when($current_room_id === 1000, function($query) use($rooms){
                $query->whereIn('room_id', array_keys($rooms));
            })
            ->where('is_temp', false)
            ->get()
            ->map(function($event) use($time_fraction, $booking_settings, $availability){
                $weekday_end_availability = $availability->where('is_open', true)->where('weekday', Carbon::parse($event->start)->dayOfWeekIso)->first();

                if($weekday_end_availability){
                    $end = $event->end;

                    //buffer
                    if($booking_settings->has_buffer && Carbon::parse($event->end)->diffInMinutes(Carbon::parse($event->end)->setTimeFromTimeString($weekday_end_availability->end)) >= 30){
                       $end = Carbon::parse($event->end)->add($time_fraction)->toDateTimeString();
                    }
    
                    return [
                        'user' => $event->user,
                        'title' => $event->room->name,
                        'start' => $event->start,
                        'end' => $end,
                        'dur' => $event->duration,
                        'guests' => $event->guests,
                        'borderColor' => $event->room->color,
                        'backgroundColor' => $event->room->color . '60',
                        'has_buffer' => $booking_settings->has_buffer,
                        'is_imported' => false,
                    ];
                } else {
                    return [];
                }
            });
        
        //recupero gli eventi da Google Calendar
        $google_events = collect([]);
        if($booking_settings->google_calendar_id){
            $google_events = Event::get(calendarId: $booking_settings->google_calendar_id)->map(function($event){
                return [
                    'start' => Carbon::parse($event->googleEvent->start->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
                    'end' => Carbon::parse($event->googleEvent->end->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
                    'title' => $event->summary,
                    'borderColor' => '#475569',
                    'backgroundColor' => '#47556930',
                    'is_imported' => true,
                ];
            });
        }

        $events = $bookings->merge($google_events); //->merge($buffer_events);

        $rooms[1000] = 'Tutte';
        $request->toArray();

        return Inertia::render('Backoffice/Studio/Bookings/Index', compact('events', 'booking_settings', 'availability', 'rooms', 'request'));
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
