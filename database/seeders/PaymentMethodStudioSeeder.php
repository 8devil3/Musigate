<?php

namespace Database\Seeders;

use App\Models\Studio\PaymentMethod;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class PaymentMethodStudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();
        
        foreach ($studios as $studio) {
            $payment_methods = PaymentMethod::limit(rand(2,13))->pluck('id');
            $studio->payment_methods()->attach($payment_methods);
        }
    }
}
