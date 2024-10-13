<?php

namespace App\Services;

use Carbon\CarbonPeriod;

class GeneratePeriodsService
{
    public static function generate(string $start = '00:00', string $end = '24:00', string $interval = '30 minutes', string $format = 'H:i'): array
    {
        $arr_periods = CarbonPeriod::create($start, $interval, $end);

        $periods = [];

        foreach ($arr_periods as $key => $period) {
            if($key === count($arr_periods) -1 && $period === '24:00'){
                $periods[] = '24:00';
            } else {
                $periods[] = $period->format($format);
            }
        }

        return $periods;
    }
}