<?php

namespace Database\Seeders;

use App\Http\Controllers\Picklists;
use App\Models\Studio\Availability;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::get();

        foreach ($studios as $studio) {
            foreach (Picklists::WEEKDAYS as $wd => $label) {
                Availability::create([
                    'studio_id' => $studio->id,
                    'weekday' => $wd,
                    'open_type' => $wd < 7 ? 'open' : 'close',
                    'open_start' => $wd < 7 ? '10:00' : null,
                    'open_end' => $wd < 7 ? '23:00' : null,
                ]);
            }
        }
    }
}
