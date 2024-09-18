<?php

namespace App\Services;

use App\Models\Room\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TimeSlotService
{
    public static function generate(Room $room, array $bookings, $request_date): array
    {
        $current_day_availability = $room->studio->availability()->where('weekday', Carbon::parse($request_date)->isoWeekday())->first();

        $period = CarbonPeriod::create($current_day_availability->start, '1 hour', $current_day_availability->end);

        $slots = [];

        $id = 0;
        foreach ($period as $index => $time) {
            $slot_start = $time->format('H:i');
            $slot_end = $time->addHour()->format('H:i');
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