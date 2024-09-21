<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\FakerImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run()
    {
        Storage::disk('public')->deleteDirectory('users');

        $users = [
            [
                'role_id' => 1,
                'first_name' => 'Aldo',
                'last_name' => 'Cocurullo',
                'email' => 'admin@admin.com',
                'email_verified_at' => now()->subDay(),
                'password' => bcrypt('qwertyuiop'),
                'avatar' => Storage::disk('public')->putFile('users/user-1/avatar/', FakerImage::image(null, 240, 240)),
                'tos' => true,
                'privacy' => true,
            ],
            [
                'role_id' => 2,
                'first_name' => '8',
                'last_name' => 'Devil',
                'email' => '8dev.il3@gmail.com',
                'email_verified_at' => now()->subDay(),
                'password' => bcrypt('qwertyuiop'),
                'avatar' => Storage::disk('public')->putFile('users/user-2/avatar/', FakerImage::image(null, 240, 240)),
                'tos' => true,
                'privacy' => true
            ],
            [
                'role_id' => 2,
                'first_name' => 'Gigi',
                'last_name' => 'Rossi',
                'email' => 'gigirossi@gmail.com',
                'email_verified_at' => now()->subDay(),
                'password' => bcrypt('qwertyuiop'),
                'avatar' => Storage::disk('public')->putFile('users/user-2/avatar/', FakerImage::image(null, 240, 240)),
                'tos' => true,
                'privacy' => true
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        for ($i=0; $i < 24; $i++) {
            $first_name = fake()->firstName();
            $last_name = fake()->lastName();

            $user = User::create([
                'role_id' => 2,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => strtolower($first_name) . '.' . strtolower($last_name) . '@' . fake()->safeEmailDomain(),
                'email_verified_at' => now()->subDay(),
                'password' => bcrypt('qwertyuiop'),
                'tos' => true,
                'privacy' => true
            ]);

            $user->update([
                'avatar' => Storage::disk('public')->putFile('users/user-' . $user->id . '/avatar/', FakerImage::image(null, 128, 128)),
            ]);
        }
    }
}
