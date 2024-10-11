<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\TempBooking;
use App\Models\User;
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
        if(!$room->is_visible) abort(404, 'Sala non trovata');
        if(!$room->is_bookable || $room->price_type === 'no_price') abort(403, 'Sala non prenotabile');

        $booking_settings = $room->studio->booking_settings;
        $time_fraction = $booking_settings->allow_fractional_durations || $booking_settings->has_buffer ? '30 minutes' : '1 hour';
        $request_duration = intval($request->duration);
        $room->load(['studio.booking_settings', 'studio.user', 'photos']);
        $slots = [];
        $events = collect([]);

        if($request->startDate){
            $request_start_date = Carbon::parse($request->startDate);
            $request_weekday = $request_start_date->dayOfWeekIso;
            $availability = $room->studio->availability()->where('is_open', true)->where('weekday', $request_weekday)->first();

            //TODO: se la sala tariffe con fasce orarie controlla che per il giorno scelto siano presenti le tariffe, esempio: $room->price_type === 'timebands_price' && !empty($room->timebands()->where('weekday', $request_start_date->isoWeekday)->get());

            if($availability){
                //recupero gli eventi (prenotazioni) di Musigate
                $bookings = $room->bookings()
                ->whereDate('start', $request_start_date->toDateString())
                ->get(['start', 'end'])
                ->map(function($event): array {
                    return [
                        'start' => $event->start,
                        'end' => $event->end
                    ];
                })->toBase();

                $temp_bookings = $room->temp_bookings()
                ->whereDate('start', $request_start_date->toDateString())
                ->get(['start', 'end'])
                ->map(function($event): array {
                    return [
                        'start' => $event->start,
                        'end' => $event->end,
                    ];
                })->toBase();

                //recupero gli eventi da Google Calendar
                $google_events = collect([]);
                if($booking_settings->has_sync && $booking_settings->google_calendar_id){
                    $request_start = Carbon::parse($request->startDate);

                    $google_events = Event::get(
                        $request_start->startOfDay(),
                        $request_start->clone()->endOfDay(),
                        [],
                        $booking_settings->google_calendar_id
                    )->map(function($event): array{
                        $start = Carbon::parse($event->googleEvent->start->dateTime)->toDateTimeString();
                        $end = Carbon::parse($event->googleEvent->end->dateTime)->toDateTimeString();

                        return [
                            'start' => $start,
                            'end' => $end,
                        ];
                    });
                }

                //riunisco gli eventi in un unica collection
                $events = $bookings->merge($temp_bookings)->merge($google_events);

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

        session()->put('room', $room);

        return Inertia::render('Frontoffice/Booking/Create', compact('events', 'slots', 'room', 'booking_settings', 'request'));
    }

    public function store(Request $request): \Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $room = session()->get('room');
        
        if(!$room->is_visible) abort(404, 'Sala non trovata');
        if(!$room->is_bookable) abort(403, 'Sala non prenotabile');

        $booking_settings = $room->studio->booking_settings;
        $max_start_dateTime = now()->addDays($booking_settings->booking_advance)->toDateTimeString();

        $request->validate([
            'start' => ['required', 'date', 'after:' . $max_start_dateTime],
            'duration' => ['required', 'integer', 'min:' . $booking_settings->min_booking, 'max:8'],
            'guests' => ['required', 'integer', 'min:1', 'max:' . $room->max_capacity],
        ]);

        $request_duration = intval($request->duration);
        $current_user = User::find(26);//auth()->user();
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->start)->addHours($request_duration);

        $temp_booking = TempBooking::create([
            'room_id' => $room->id,
            'user_id' => $current_user->id,
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString(),
            'guests' => $request->guests,
            'duration' => $request_duration,
            'qr_code' => \Str::uuid()->toString(),
        ]);

        //copio la prenotazione dalla tabella temporanea
        $booking = $temp_booking->replicate();
        $booking->setTable('bookings');
        $booking->save();

        // Booking::create([
        //     'room_id' => $room->id,
        //     'user_id' => $current_user->id,
        //     'start' => $start->toDateTimeString(),
        //     'end' => $end->toDateTimeString(),
        //     'guests' => $request->guests,
        //     'duration' => $request_duration,
        //     'qr_code' => \Str::uuid()->toString(),
        // ]);

        //TODO: elaborazione pagamento Stripe
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

            //TODO: generazione immagine QR Code

            // return Inertia::location($checkout_session->url);
        // }

        //inserimento nel calndario google collegato. 
        $has_google_calendar_scope = in_array(config('google-calendar.scope'), $room->studio->user->approved_scopes);

        if($booking_settings->has_sync && $has_google_calendar_scope && $booking_settings->google_calendar_id && $booking_settings->sync_mode === 'bidirezionale'){
            $google_event_id = str_replace('-', '', 'musigate' . \Str::uuid());

            $new_google_event = Event::create([
                'id' => $google_event_id,
                'name' => 'Musigate - Prenotazione ' . $room->name,
                'startDateTime' => $start,
                'endDateTime' => $end,
                'location' => $room->studio->location->complete_address,
                'attendees' => [
                    [
                        'email' => $current_user->email,
                        'displayName' => $current_user->first_name,
                        'responseStatus' => 'accepted',
                    ],
                ],
                'source' => [
                    'title' => 'Musigate',
                    'url' => 'https://www.musigate.it'
                ],
                'description' => 'Prenotazione effettuata da ' . $current_user->first_name . ' ' . $current_user->last_name . ' su Musigate.'
            ], $booking_settings->google_calendar_id);

            $new_google_event->addAttendee([
                'email' => $current_user->email,
                'name' => $current_user->first_name,
                'responseStatus' => 'accepted',
            ]);
        }

        session()->forget('room');

        return back()->with('success', 'Sala prenotata con successo');
    }
}
