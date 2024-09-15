<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio\Studio;
use App\Models\Studio\Service;

class StudioServiceSeeder extends Seeder
{
    /**
        * Run the database seeds.
        *
        * @return void
        */
    public function run()
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            $studioServices = Service::inRandomOrder()->limit(5)->get();

            $studio->services()->attach($studioServices->pluck('id')->all());
        }
    }
}
