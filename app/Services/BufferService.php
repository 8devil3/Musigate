<?php

namespace App\Services;

use Carbon\Carbon;

class BufferService
{
    public static function add_buffer(bool $has_buffer, string $end, $availability_end, int $buffer_value = 30): Carbon
    {
        $event_end = Carbon::parse($end);
        $end = $event_end->clone();

        if($has_buffer && $end->diffInMinutes($event_end->setTimeFromTimeString($availability_end)) >= $buffer_value){
            return $end->addMinutes($buffer_value);
        } else {
            return $event_end;
        }
    }
}