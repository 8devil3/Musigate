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
            'Mixer',
            'Outboard',
            'Monitoring',
            'Computer e software',
            'Strumenti musicali',
            'Sintetizzatori',
            'Microfoni',
            'DAW',
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
