<?php
namespace Database\Seeders;

use App\Models\Status\RoomStatus;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $room_statuses = [
            'Respinta',
            'Sospesa',
            'In approvazione',
            'Pubblicata',
            'Nuova',
        ];

        foreach ($room_statuses as $room_status) {
            RoomStatus::create(['name' => $room_status]);
        }
    }
}
