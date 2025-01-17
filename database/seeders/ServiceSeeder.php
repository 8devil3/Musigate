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
        $services = [
            'fonico',
            // 'mastering',
            // 'remastering',
            // 'mixing',
            // 'editing',
            'pre/post produzione',
            'arrangiamenti',
            'podcasting',
            'consulenza artistica',
            'promozione artistica',
            'produzione musicale',
            'produzione demo',
            'pratiche SIAE',
            'video making/editing',
            'noleggio strumenti musicali',
            'turnisti',
            'composizione musiche, testi',
            'produzione, grafica, stampa CD',
            'campionature',
            'corsi/lezioni',
            'sound design',
            'voice over',
            'doppiaggio',
            'service audio/luci',
            'trascrizioni',
            'duplicazioni',
            'restauro audio/video',
            'riversamenti',
            'authoring',
            'videoclip',
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => $service,
            ]);
        }
    }
}
