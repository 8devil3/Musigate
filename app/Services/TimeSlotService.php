<?php

namespace App\Services;

use App\Models\Studio\Availability;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;

class TimeSlotService
{
    public static function generate(Collection $availability, array $bookings, string $request_date, int $buffer = 0): array
    {
        $current_day_availability = $availability->where('weekday', Carbon::parse($request_date)->isoWeekday())->first();

        $period = CarbonPeriod::create($current_day_availability->start, '1 hour', $current_day_availability->end);

        $slots = [];

        $id = 0;
        foreach ($period as $index => $time) {
            $slot_start = $time->format('H:i');
            if($index !== 0) $slot_start = $time->addMinutes($buffer)->format('H:i');
            $slot_end = $time->addHour()->addMinutes($buffer)->format('H:i');
            $is_available = true;

            if(!empty($bookings)){
                foreach ($bookings as $booking) {
                    if(Carbon::parse($request_date . 'T' . $slot_start)->isBetween(
                            Carbon::parse($booking['start']),
                            Carbon::parse($booking['end']),
                            false
                        ) ||
                            Carbon::parse($request_date . 'T' . $slot_end)->isBetween(
                                Carbon::parse($booking['start']),
                                Carbon::parse($booking['end']),
                                false
                        ) || (
                            Carbon::parse($booking['start'])->equalTo(Carbon::parse($request_date . 'T' . $slot_start)) &&
                            Carbon::parse($booking['end'])->equalTo(Carbon::parse($request_date . 'T' . $slot_end))
                    )) $is_available = false;
                }
            }

            if($index !== count($period) -1){
                $slots[] = [
                    'id' => $id++,
                    'start' => $slot_start,
                    'end' => $slot_end,
                    'is_available' => $is_available,
                ];
            }
        }

        return $slots;
    }
}