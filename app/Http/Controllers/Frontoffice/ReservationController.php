<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\GoogleCalendar\Event;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ReservationController extends Controller
{
    public function create(Request $request, Room $room): Response
    {
        $booking_settings = $room->studio->booking_settings;
        $time_fraction = $booking_settings->allow_fractional_durations || $booking_settings->has_buffer ? '30 minutes' : '1 hour';
        $request_duration = intval($request->duration);
        $room->load('photos');
        $slots = [];
        $events = [];

        if($request->startDate){
            $request_start_date = Carbon::parse($request->startDate);
            $request_weekday = $request_start_date->dayOfWeekIso;
            $availability = $room->studio->availability()->where('is_open', true)->where('weekday', $request_weekday);

            if($availability->exists()){
                //recupero gli eventi (prenotazioni) di Musigate
                $booking_events = $room->bookings()
                    ->whereDate('start', $request_start_date->toDateString())
                    ->get(['start', 'end'])
                    ->map(function($event){
                        return [
                            'title' => 'Occupato',
                            'start' => $event->start,
                            'end' => $event->end,
                            'borderColor' => '#b91c1c',
                            'backgroundColor' => '#450a0a',
                        ];
                    });

                //recupero gli eventi da Google Calendar
                $google_events = collect([]);
                if($booking_settings->google_calendar_id){
                    $google_events = Event::get(calendarId: $booking_settings->google_calendar_id)
                        ->filter(function($event) use($request_start_date): bool{
                            return Carbon::parse($event->googleEvent->start->dateTime)->setTimezone('Europe/Rome')->toDateString() === $request_start_date->toDateString();
                        })
                        ->map(function($event){
                            return [
                                'start' => Carbon::parse($event->googleEvent->start->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
                                'end' => Carbon::parse($event->googleEvent->end->dateTime)->setTimezone('Europe/Rome')->toDateTimeString(),
                            ];
                        });
                }

                //genero gli eventi di buffer
                $buffer_events = collect([]);
                if($booking_settings->has_buffer){
                    $buffer_events = $booking_events->merge($google_events)
                        ->map(function($event) use($time_fraction){
                            return [
                                'start' => $event['end'],
                                'end' => Carbon::parse($event['end'])->add($time_fraction)->toDateTimeString(),
                            ];
                        });
                }

                //riunisco gli eventi in un unica collection
                $events = $buffer_events->merge($google_events)->merge($booking_events);

                //genero gli slot per la selezione dell'orario iniziale
                $period_start = $request_start_date->setTimeFromTimeString($availability->first()->start)->toImmutable();
                $period_end = $request_start_date->setTimeFromTimeString($availability->first()->end)->subHours($request_duration);

                $periods = CarbonPeriod::create($period_start, $time_fraction, $period_end)
                    ->filter(function(Carbon $period) use($events, $request_duration): bool {
                        $is_available = true;

                        $period_start = $period->toImmutable();
                        $period_plus_duration = $period_start->addHours($request_duration);

                        $request_period = CarbonPeriod::create($period_start, $period_plus_duration);

                        foreach ($events as $event) {
                            $event_period = CarbonPeriod::create($event['start'], $event['end']);

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

        return Inertia::render('Frontoffice/Booking/Create', compact('events', 'slots', 'room', 'booking_settings', 'request'));
    }

    public function store(Room $room, Request $request): \Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $booking_settings = $room->studio->booking_settings;

        $request->validate([
            'start' => ['required', 'date', 'after:' . now()->addDays($booking_settings->booking_advance)->toDateTimeString()],
            'duration' => ['required', 'integer', 'min:' . $booking_settings->min_booking, 'max:8'],
            'guests' => ['required', 'integer', 'min:1', 'max:' . $room->max_capacity],
        ]);

        $request_duration = intval($request->duration);
        $current_user = auth()->user();
        $start = Carbon::parse($request->start)->toDateTimeString();
        $end = Carbon::parse($request->start)->addHours($request_duration)->toDateTimeString();

        $is_temp_booking_created = Booking::create([
            'room_id' => $room->id,
            'user_id' => $current_user->id,
            'start' => $start,
            'end' => $end,
            'guests' => $request->guests,
            'duration' => $request_duration,
            'qr_code' => \Str::uuid()->toString(),
            'is_temp' => true, //prenotazione temporanea da completare. In attesa che l'utente completi il pagamento, blocco il calendario in modo da garantire lo slot di prenotazione per quell'utente. Quando l'utente completa il pagamento e ricevo il webhook da Stripe, allora la prenotazione diventa definitiva.
        ]);

        //elaborazione pagamento Stripe
        // if($is_temp_booking_created){
            // Stripe::setApiKey(config('cashier.secret'));

            // $checkout_session = Session::create([
            //     'success_url' => route('company.billing.credits.success'),
            //     'cancel_url' => route('company.billing.credits'),
            //     'customer' => $room->studio->user->billing->stripe_id,
            //     'line_items' => [
            //         [
            //             'quantity' => $request_duration,
            //             'price_data' => [
            //                 'currency' => 'eur',
            //                 'product' => config('custom.stripe.product_id'),
            //                 'unit_amount' => $room->price
            //             ],
            //         ]
            //     ],
            //     'mode' => 'payment',
            //     'metadata' => [
            //         'durata' => $request->duration,
            //         'user_id' => $user->id,
            //         'studio_id' => $room->studio->id,
            //         'studio_name' => $room->studio->name,
            //         'room_id' => $room->id,
            //         'room_name' => $room->name,
            //     ],
            // ]);

            // return Inertia::location($checkout_session->url);
        // }

        // return back()->withErrors('error', 'Si è verificato un problema con il pagamento.');

    }
}
