<?php
namespace Database\Seeders;

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
        $services = [
            'fonico',
            'mastering',
            'remastering',
            'mixing',
            'editing',
            'pre-produzione',
            'post-produzione',
            'arrangiamenti',
            'consulenza artistica',
            'promozione artistica',
            'produzione musicale',
            'produzione demo',
            'pratiche SIAE',
            'video making',
            'video editing',
            'noleggio strumenti musicali',
            'turnisti',
            'composizione musiche',
            'composizione testi',
            'produzione CD',
            'grafica/stampa CD',
            'campionature',
            'corsi',
            'sound design',
            'voice over',
            'doppiaggio'
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => $service
            ]);
        }
    }
}
