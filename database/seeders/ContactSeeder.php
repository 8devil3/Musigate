<?php

namespace Database\Seeders;

use App\Models\Studio\Contact;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            Contact::create([
                'studio_id' => $studio->id,
                'email' => $studio->user->email,
                'phone' => fake()->phoneNumber(),
                'telegram' => 'https://t.me/' . fake()->userName(),
                'messenger' => 'https://m.me/' . fake()->userName(),
                'whatsapp' => 'https://wa.me/' . fake()->phoneNumber(),
            ]);
        }
    }
}
