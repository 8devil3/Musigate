<?php

namespace Database\Seeders;

use App\Models\Studio\Studio;
use App\Models\Studio\StudioPhoto;
use App\Services\FakerImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StudioPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            for ($i=0; $i < 12; $i++) { 
                StudioPhoto::create([
                    'studio_id' => $studio->id,
                    'is_featured' => $i === 1 ? true : false,
                    'path' => Storage::disk('public')->putFile('users/user-' . $studio->user_id . '/studio/photos', FakerImage::image()),
                ]);
            }
        }
    }
}
