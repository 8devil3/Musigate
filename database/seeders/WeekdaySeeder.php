<?php

namespace Database\Seeders;

use App\Models\Weekday;
use Illuminate\Database\Seeder;

class WeekdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weekdays = [
            [
                'name' => 'Lunedì',
                'name_short' => 'Lun',
                'name_en' => 'monday',
                'rrule_name' => 'MO'
            ],
            [
                'name' => 'Martedì',
                'name_short' => 'Mar',
                'name_en' => 'tuesday',
                'rrule_name' => 'TU'
            ],
            [
                'name' => 'Mercoledì',
                'name_short' => 'Mer',
                'name_en' => 'wednesday',
                'rrule_name' => 'WE'
            ],
            [
                'name' => 'Giovedì',
                'name_short' => 'Gio',
                'name_en' => 'thursday',
                'rrule_name' => 'TH'
            ],
            [
                'name' => 'Venerdì',
                'name_short' => 'Ven',
                'name_en' => 'friday',
                'rrule_name' => 'FR'
            ],
            [
                'name' => 'Sabato',
                'name_short' => 'Sab',
                'name_en' => 'saturday',
                'rrule_name' => 'SA'
            ],
            [
                'name' => 'Domenica',
                'name_short' => 'Dom',
                'name_en' => 'sunday',
                'rrule_name' => 'SU'
            ],
        ];

        foreach ($weekdays as $wd) {
            Weekday::create($wd);
        }
    }
}
