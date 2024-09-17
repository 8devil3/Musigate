<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        $studio_users = User::studioUser()->inRandomOrder()->limit(8)->get();

        foreach ($rooms as $room) {
            foreach ($studio_users as $user) {
                $start = Carbon::parse(fake()->dateTimeBetween('-3 months', '3 months'))->hour(rand(10, 19))->hour(0)->second(0)->toDateTimeString();
                $duration = rand(2,4);
                $end = Carbon::parse($start)->addHours($duration);

                Booking::create([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'start' => $start,
                    'duration' => $duration,
                    'end' => $end,
                    'qr_code' => fake()->uuid(),
                ]);
            }
        }
    }
}
