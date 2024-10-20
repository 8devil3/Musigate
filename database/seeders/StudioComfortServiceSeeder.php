<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio\Studio;
use App\Models\Studio\Comfort;
use App\Models\Studio\Service;

class StudioComfortServiceSeeder extends Seeder
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
            $studio_comfort = Comfort::inRandomOrder()->limit(rand(4,16))->get();
            $studio->comforts()->attach($studio_comfort->pluck('id')->all());

            $studio_service = Service::inRandomOrder()->limit(rand(4,16))->get();
            $studio->services()->attach($studio_service->pluck('id')->all());
        }
    }
}
