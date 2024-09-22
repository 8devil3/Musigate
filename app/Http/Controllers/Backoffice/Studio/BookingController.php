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

        // if(!empty($request->toArray())){
        //     session()->put('request', $request->toArray());
        //     $request = session('request');
        // }

        $room->load('photos');

        $week_availability = $room->studio->availability()->get(['weekday', 'start', 'end', 'is_open']);

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
                    'start' => Carbon::parse($event->googleEvent->start->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
                    'end' => Carbon::parse($event->googleEvent->end->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
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

        $slots = [];

        if($request->startDate && Carbon::parse($request->startDate)->isAfter(now())){
            $request_weekday = Carbon::parse($request->startDate)->dayOfWeekIso;
            $availability = $room->studio->availability()->where('is_open', true)->where('weekday', $request_weekday);

            if($availability->exists()){
                $request_weekday_availability_start = $availability->first()->start;
                $request_weekday_availability_end = $availability->first()->end;

                $period_start = Carbon::parse($request->startDate . ' ' . $request_weekday_availability_start);
                $period_end = Carbon::parse($request->startDate . ' ' . $request_weekday_availability_end)->subHours(intval($request->duration));
    
                $booking_events = $room->bookings()->whereDate('start', $request->startDate)->get(['start', 'end']);
    
                $periods = CarbonPeriod::create($period_start, '1 hour', $period_end)
                    ->filter(function(Carbon $period) use($booking_events, $request): bool {
                        $is_available = true;
    
                        $period_start = $period->toImmutable();
                        $period_plus_duration = $period_start->addHours(intval($request->duration));
    
                        $request_period = CarbonPeriod::create($period_start, $period_plus_duration);
    
                        foreach ($booking_events as $event) {
                            $event_period = CarbonPeriod::create($event->start, $event->end);
    
                            if($request_period->overlaps($event_period)){
                                $is_available = false;
                                break;
                            }
                        }
    
                        return $is_available;
                    });
    
                foreach ($periods as $period) {
                    $slots[] = $period->toDateTimeString();
                }
            }
        }

        return Inertia::render('Frontoffice/Booking/Create', compact('events', 'slots', 'room', 'week_availability', 'booking_settings', 'closing_weekdays', 'request'));
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
