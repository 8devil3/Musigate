<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio\Comfort;

class ComfortSeeder extends Seeder
{
    /**
        * Run the database seeds.
        *
        * @return void
        */
    public function run()
    {
        $comforts = [
            'WiFi free',
            'parcheggio privato',
            'guardaroba',
            'dist. automatici',
            '420 friendly',
            'montacarichi',
            'ascensore',
            'ingresso carrabile',
            'facchinaggio',
            'LGBTQ+ friendly',
            'TV',
            'cucina',
            'toilette',
            'area relax',
            'area fumatori',
            'free coffee',
            'camerini riservati',
            'aria condizionata',
            'aria sanificata',
            'videocamere sorveglianza',
            'vigilanza notturna',
        ];

        foreach ($comforts as $comfort) {
            Comfort::create([
                'name' => $comfort
            ]);
        }
    }
}
