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
