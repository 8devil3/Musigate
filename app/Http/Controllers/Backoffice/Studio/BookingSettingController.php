<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarAPIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingSettingController extends Controller
{
    public function edit(): Response
    {
        $user = auth()->user();
        $booking_settings = $user->studio->booking_settings;

        $has_paypal = $user->paypal_access_token;
        $has_google_id = $user->google_id ? true : false;
        $google_calendar_ids = [];
        $has_google_calendar_scope = false;

        if($user->google_scopes && in_array(config('google-calendar.scope'), $user->google_scopes)){
            $google_token = $user->google_token;
            $google_calendars = GoogleCalendarAPIService::get_calendars($user->google_token);
            $has_google_calendar_scope = true;

            foreach ($google_calendars as $calendar) {
                $google_calendar_ids[] = $calendar->getId();
            }
        }

        return Inertia::render('Backoffice/Studio/Bookings/BookingSettings', compact('booking_settings', 'google_calendar_ids', 'has_google_id', 'has_google_calendar_scope', 'has_paypal'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'booking_advance' => 'required|integer|min:0|max:96',
            'max_booking_horizon' => 'required|integer|min:7|max:365',
            'has_buffer' => 'boolean',
            'allow_fractional_durations' => 'accepted_if:has_buffer,true|boolean',
            'has_sync' => 'boolean',
            'sync_mode' => 'nullable|required_if_accepted:has_sync|string|in:unidirezionale,bidirezionale',
            'google_calendar_id' => 'nullable|required_if_accepted:has_sync|string|max:255',
            'default_calendar_view' => 'required|string|in:dayGridMonth,timeGridWeek',
            'buffer_on_imported_event' => 'boolean',
        ]);

        $user = auth()->user();
        $has_google_calendar_scope = $user->google_scopes && in_array(config('google-calendar.scope'), $user->google_scopes);

        $user->studio->booking_settings->update([
            'booking_advance' => $request->booking_advance,
            'max_booking_horizon' => $request->max_booking_horizon,
            'has_buffer' => $request->has_buffer,
            'allow_fractional_durations' => $request->has_buffer ? true : $request->allow_fractional_durations,
            'has_sync' => $request->has_sync && $has_google_calendar_scope ? true : false,
            'sync_mode' => $request->has_sync && $has_google_calendar_scope ? $request->sync_mode : null,
            'google_calendar_id' => $request->has_sync && $has_google_calendar_scope ? $request->google_calendar_id : null,
            'default_calendar_view' => $request->default_calendar_view,
            'buffer_on_imported_event' => !$request->has_buffer ? false : $request->buffer_on_imported_event,
        ]);

        return back()->with('success', 'Impostazioni prenotazioni salvate');
    }
}
