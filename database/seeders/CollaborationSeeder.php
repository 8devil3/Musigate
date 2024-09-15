<?php

namespace Database\Seeders;

use App\Models\Studio\Collaboration;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class CollaborationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            for ($c=1; $c < 8; $c++) {
                Collaboration::create([
                    'studio_id' => $studio->id,
                    'title' => fake()->words(rand(2,4), true),
                    'desc' => fake()->paragraph(),
                ]);
            }
        }
    }
}
