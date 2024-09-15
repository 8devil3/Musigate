<?php

namespace Database\Seeders;

use App\Models\Studio\Social;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            Social::create([
                'studio_id' => $studio->id,
                'facebook' => 'https://it-it.facebook.com/' . fake()->slug(),
                'instagram' => 'https://www.instagram.com/' . fake()->slug(),
                'linkedin' => 'https://www.linkedin.com/' . fake()->slug(),
                'youtube' => 'https://www.youtube.com/' . fake()->slug(),
                'soundcloud' => 'https://www.soundcloud.com/' . fake()->slug(),
                'itunes' => 'https://www.itunes.com/' . fake()->slug(),
                'spotify' => 'https://www.spotify.com/' . fake()->slug(),
                'website' => fake()->url(),
            ]);
        }
    }
}
