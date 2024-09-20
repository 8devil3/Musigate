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
            for ($i = 0; $i <= 6; $i++){
                Availability::create([
                    'studio_id' => $studio->id,
                    'weekday' => $i,
                    'start' => $i === 0 ? null : '10:00',
                    'end' => $i === 0 ? null : '23:00',
                    'is_open' => $i === 0 ? false : true,
                ]);
            }
        }
    }
}
