<?php
namespace Database\Seeders;

use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;
use App\Models\Studio\Service;

class ServiceSeeder extends Seeder
{
    /**
        * Run the database seeds.
        *
        * @return void
        */
    public function run()
    {
        // $services = [
        //     'fonico',
        //     'mastering',
        //     'remastering',
        //     'mixing',
        //     'editing',
        //     'pre-produzione',
        //     'post-produzione',
        //     'arrangiamenti',
        //     'consulenza artistica',
        //     'promozione artistica',
        //     'produzione musicale',
        //     'produzione demo',
        //     'pratiche SIAE',
        //     'video making',
        //     'video editing',
        //     'noleggio strumenti musicali',
        //     'turnisti',
        //     'composizione musiche',
        //     'composizione testi',
        //     'produzione CD',
        //     'grafica/stampa CD',
        //     'campionature',
        //     'corsi',
        //     'sound design',
        //     'voice over',
        //     'doppiaggio'
        // ];

        
        $studios = Studio::all();

        foreach ($studios as $studio) {
            $price = rand(300, 800);

            Service::create([
                'studio_id' => $studio->id,
                'name' => fake()->words(rand(2,4), true),
                'description' => fake()->text(),
                'price' => $price,
                'discounted_price' => $price -100,
            ]);
        }
    }
}
