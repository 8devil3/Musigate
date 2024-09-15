<?php

namespace Database\Seeders;

use App\Models\Studio\Rule;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            Rule::create([
                'studio_id' => $studio->id,
                'before' => fake()->paragraphs(rand(1, 3), true),
                'during' => fake()->paragraphs(rand(1, 3), true),
                'after' => fake()->paragraphs(rand(1, 3), true),
            ]);
        }
    }
}
