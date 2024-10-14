<?php

namespace Database\Seeders;

use App\Models\Studio\BookingSetting;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class BookingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            BookingSetting::create([
                'studio_id' => $studio->id
            ]);
        }
    }
}
