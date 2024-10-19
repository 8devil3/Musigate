<?php

namespace Database\Seeders;

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
            for ($i = 1; $i <= 7; $i++){
                Availability::create([
                    'studio_id' => $studio->id,
                    'weekday' => $i,
                    'open_type' => $i === 7 ? 'open' : 'close',
                    'open_start' => $i === 7 ? null : '10:00',
                    'open_end' => $i === 7 ? null : '23:00',
                ]);
            }
        }
    }
}
