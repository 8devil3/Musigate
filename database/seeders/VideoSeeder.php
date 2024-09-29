<?php

namespace Database\Seeders;

use App\Models\Studio\Studio;
use App\Models\Studio\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            $yt_id = fake()->bothify('??#?##?#???');

            Video::create([
                'studio_id' => $studio->id,
                'url' => 'https://www.youtube.com/watch?v=' . $yt_id,
                'yt_id' => $yt_id
            ]);
        }
    }
}
