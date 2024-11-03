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
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => '8dev.il3@gmail.com',
                'email_verified_at' => now()->subDay(),
                'password' => bcrypt('qwertyuiop'),
                'avatar' => null,
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
                'avatar' => null,
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
        }
    }
}
