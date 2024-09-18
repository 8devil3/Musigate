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

        return Inertia::render('Backoffice/Studio/BookingSettings', compact('booking_settings', 'google_calendar_ids', 'google_token'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'min_booking' => 'required|integer|min:1|max:8',
            'booking_advance' => 'required|integer|min:0|max:96',
            'max_booking_horizon' => 'required|integer|min:7|max:365',
            'has_buffer' => 'boolean',
            'buffer' => 'nullable|required_if_accepted:has_buffer|integer|min:5|max:60',
            'has_sync' => 'boolean',
            'sync_mode' => 'nullable|required_if_accepted:has_sync|string|in:unidirezionale,bidirezionale',
            'google_calendar_id' => 'nullable|required_if_accepted:has_sync|string|max:255',
        ]);

        auth()->user()->studio->booking_settings->update([
            'min_booking' => $request->min_booking,
            'booking_advance' => $request->booking_advance,
            'max_booking_horizon' => $request->max_booking_horizon,
            'has_buffer' => $request->has_buffer,
            'buffer' => $request->has_buffer ? $request->buffer : null,
            'has_sync' => $request->has_sync,
            'sync_mode' => $request->has_sync ? $request->sync_mode : null,
            'google_calendar_id' => $request->has_sync ? $request->google_calendar_id : null,
        ]);

        return back();
    }
}
