<?php

namespace App\Services;

use Carbon\CarbonPeriod;

class GeneratePeriodsService
{
    public static function generate(string $start = '00:00', string $end = '23:30', string $interval = '30 minutes', string $format = 'H:i'): array
    {
        $arr_periods = CarbonPeriod::create($start, $interval, $end);

        $periods = [];
        foreach ($arr_periods as $key => $period) {
            $periods[] = $period->format($format);
        }

        return $periods;
    }
}