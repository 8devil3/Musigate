<?php

namespace Database\Seeders;

use App\Models\Studio\CancelPolicySetting;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class CancelPolicySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            CancelPolicySetting::create(['studio_id' => $studio->id]);
        }
    }
}
