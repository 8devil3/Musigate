<?php

namespace Database\Seeders;

use App\Models\Studio\Location;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            Location::create([
                'studio_id' => $studio->id,
                'complete_address' => fake()->address(),
                'address' => fake()->streetName(),
                'number' => fake()->buildingNumber(),
                'city' => fake()->city(),
                'province' => 'Provincia di ' . fake()->state(),
                'cap' => fake()->postcode(),
                'lon' => fake()->longitude(7.8, 13.3),
                'lat' => fake()->latitude(44, 46.5),
                'notes' => fake()->text(150)
            ]);
        }
    }
}
