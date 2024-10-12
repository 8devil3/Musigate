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

        Storage::disk('public')->deleteDirectory('studio');

        foreach ($studios as $studio) {
            for ($i=0; $i < 6; $i++) {
                $path = Storage::disk('public')->putFile('studios/studio-' . $studio->id . '/photos', FakerImage::image());

                StudioPhoto::create([
                    'studio_id' => $studio->id,
                    'path' => $path,
                    'filename' => basename($path),
                    'sort_index' => $i +1,
                ]);
            }
        }
    }
}
