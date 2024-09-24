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
        $google_token = auth()->user()->google_token;

        $booking_settings = auth()->user()->studio->booking_settings;
        $google_calendars = GoogleCalendarAPIService::get_calendars($google_token);

        $google_calendar_ids = [];

        foreach ($google_calendars as $calendar) {
            $google_calendar_ids[] = $calendar->getId();
        }

        return Inertia::render('Backoffice/Studio/Settings/BookingSettings', compact('booking_settings', 'google_calendar_ids', 'google_token'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'min_booking' => 'required|integer|min:1|max:8',
            'booking_advance' => 'required|integer|min:0|max:96',
            'max_booking_horizon' => 'required|integer|min:7|max:365',
            'has_buffer' => 'required|boolean',
            'allow_fractional_durations' => 'required|boolean',
            'has_sync' => 'required|boolean',
            'sync_mode' => 'nullable|required_if_accepted:has_sync|string|in:unidirezionale,bidirezionale',
            'google_calendar_id' => 'nullable|required_if_accepted:has_sync|string|max:255',
            'default_calendar_view' => 'required|string|in:dayGridMonth,timeGridWeek'
        ]);

        auth()->user()->studio->booking_settings->update([
            'min_booking' => $request->min_booking,
            'booking_advance' => $request->booking_advance,
            'max_booking_horizon' => $request->max_booking_horizon,
            'has_buffer' => $request->has_buffer,
            'allow_fractional_durations' => $request->has_buffer ? true : $request->allow_fractional_durations,
            'has_sync' => $request->has_sync,
            'sync_mode' => $request->has_sync ? $request->sync_mode : null,
            'google_calendar_id' => $request->has_sync ? $request->google_calendar_id : null,
            'default_calendar_view' => $request->default_calendar_view
        ]);

        return back();
    }
}
