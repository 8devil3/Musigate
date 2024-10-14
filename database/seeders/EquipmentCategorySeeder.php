<?php

namespace Database\Seeders;

use App\Models\Room\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Console',
            'Mixer',
            'Outboard',
            'Monitoring',
            'Computer e software',
            'Master clock',
            'Strumenti musicali',
            'Sintetizzatori',
            'Reverbs',
            'Microfoni',
            'DAW',
            'Converters',
            'FX processors',
            'Backline',
            'Dynamics',
            'Amplificatori/Pre-amplificatori',
            'Processori vocali',
            'Groovebox',
            'Finalizer',
            'Harmonizer',
        ];

        foreach ($categories as $category) {
            EquipmentCategory::create([
                'name' => $category
            ]);
        }
    }
}
