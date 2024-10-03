<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room\Room;
use App\Services\BrightnessColorService;
use App\Services\BufferService;
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
        $studio = auth()->user()->studio;
        $booking_settings = $studio->booking_settings;
        $availability = $studio->availability;
        $is_open_24_7 = $studio->ais_open_24_7;
        $time_fraction = $booking_settings->allow_fractional_durations || $booking_settings->has_buffer ? '30 minutes' : '1 hour';

        $current_room_id = request('room_id', 'all');
        $rooms = $studio->rooms()->pluck('name', 'id')->toArray();

        $events = collect([]);
        $bookings = collect([]);

        if(!empty($rooms)){
            $bookings = Booking::with(['room:id,name,color', 'user:id,first_name,last_name'])
                ->when($current_room_id !== 'all', function($query) use($current_room_id){
                    $query->where('room_id', $current_room_id);
                })
                ->when($current_room_id === 'all', function($query) use($rooms){
                    $query->whereIn('room_id', array_keys($rooms));
                })
                ->where('is_temp', false)
                ->get()
                ->map(function($event) use($booking_settings, $availability): array{
                    $weekday_availability = $availability->where('is_open', true)->where('weekday', Carbon::parse($event->start)->dayOfWeekIso)->first();

                    if($weekday_availability){
                        $end = BufferService::add_buffer($booking_settings->has_buffer, $event->end, $weekday_availability->end);

                        $bg_color = BrightnessColorService::set_brightness($event->room->color, -0.6);

                        return [
                            'user' => $event->user,
                            'title' => $event->room->name,
                            'start' => $event->start,
                            'end' => $end,
                            'dur' => $event->duration,
                            'guests' => $event->guests,
                            'borderColor' => $event->room->color,
                            'backgroundColor' => $bg_color,
                            'has_buffer' => $booking_settings->has_buffer,
                            'is_imported' => false,
                        ];
                    } else {
                        return [];
                    }
                })->toBase();
        }

        //recupero gli eventi da Google Calendar
        $google_events = collect([]);
        if($booking_settings->has_sync && $booking_settings->google_calendar_id){
            $border_color = '#64748b';
            $bg_color = BrightnessColorService::set_brightness($border_color, -0.6);

            $google_events = Event::get(
                now()->subMonths(6),
                now()->addYear(),
                [],
                $booking_settings->google_calendar_id
            )->map(function($event) use($booking_settings, $availability, $bg_color): array{
                $start = Carbon::parse($event->googleEvent->start->dateTime);
                $weekday_availability = $availability->where('is_open', true)->where('weekday', $start->dayOfWeekIso)->first();

                if(!\Str::startsWith($event->id, 'musigate') && $weekday_availability){
                    $end = Carbon::parse($event->googleEvent->end->dateTime);

                    if($booking_settings->buffer_on_imported_event){
                        $end = BufferService::add_buffer($booking_settings->has_buffer, $event->googleEvent->end->dateTime, $weekday_availability->end);
                    }

                    return [
                        'start' => $start->toDateTimeString(),
                        'end' => $end->toDateTimeString(),
                        'title' => $event->summary,
                        'borderColor' => '#475569',
                        'backgroundColor' => $bg_color,
                        'is_imported' => true,
                        'has_buffer' => $booking_settings->buffer_on_imported_event,
                    ];
                } else {
                    return [];
                }
            });
        }

        //merging degli events
        $events = $bookings->merge($google_events);

        $rooms['all'] = 'Tutte le Sale';
        $request->toArray();

        return Inertia::render('Backoffice/Studio/Bookings/Index', compact('events', 'booking_settings', 'is_open_24_7', 'availability', 'rooms', 'request'));
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
