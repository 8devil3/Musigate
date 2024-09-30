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
        $categories = ['Professional', 'Home'];

        $users = User::whereNot('id', 1)->get();

        foreach ($users as $user) {
            $category = $categories[rand(0, count($categories) -1)];

            $studio = Studio::create([
                'user_id' => $user->id,
                'name' => 'Studio ' . fake()->company(),
                'vat' => $category === 'Professional' ? str_replace('IT', '', fake()->vat()) : null,
                'logo' => null,
                'category' => $category,
                'record_label' => fake()->boolean(),
                'description' => fake()->text(rand(800, 1600)),
                'is_visible' => fake()->boolean(),
                'is_complete' => true,
            ]);

            $studio->update([
                'logo' => Storage::disk('public')->putFile('studios/studio-' . $studio->id . '/logo', FakerImage::image(null, 240, 240))
            ]);
        }
    }
}
