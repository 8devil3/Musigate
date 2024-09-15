<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio\Studio;
use App\Models\Studio\Comfort;

class StudioComfortSeeder extends Seeder
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
            $studioComfort = Comfort::inRandomOrder()->limit(8)->get();

            $studio->comforts()->attach($studioComfort->pluck('id')->all());
        }
    }
}
