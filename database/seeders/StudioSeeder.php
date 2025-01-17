<?php

namespace Database\Seeders;

use App\Models\Studio\Studio;
use App\Models\User;
use App\Services\FakerImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereNot('id', 1)->get();

        Storage::disk('public')->deleteDirectory('studios');

        $categories = ['Professional', 'Home'];

        foreach ($users as $user) {
            $name = 'Studio ' . fake()->company();

            $studio = Studio::create([
                'user_id' => $user->id,
                'name' => 'Studio ' . fake()->company(),
                'category' => $categories[rand(0,1)],
                'slug' => \Str::slug($name),
                'vat' => str_replace('IT', '', fake()->vat()),
                'logo' => null,
                'description' => fake()->text(rand(800, 1600)),
                'is_record_label' => fake()->boolean(),
                'is_published' => true,
                'is_complete' => true,
            ]);

            $logo_path = Storage::disk('public')->putFile('studios/studio-' . $studio->id . '/logo', FakerImage::image(null, 160, 160));

            $studio->update([
                'logo' => $logo_path,
            ]);
        }
    }
}
