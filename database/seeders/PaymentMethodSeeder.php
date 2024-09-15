<?php

namespace Database\Seeders;

use App\Models\Studio\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pay_methods = [
            [
                'name' => 'Contante',
                'img_name' => 'contante.svg'
            ],
            [
                'name' => 'Bancomat',
                'img_name' => 'bancomat.svg'
            ],
            [
                'name' => 'American Express',
                'img_name' => 'Amex.svg'
            ],
            [
                'name' => 'Diners Club',
                'img_name' => 'DinersClub.svg'
            ],
            [
                'name' => 'Apple Pay',
                'img_name' => 'ApplePay.svg'
            ],
            [
                'name' => 'Google Pay',
                'img_name' => 'GooglePay.svg'
            ],
            [
                'name' => 'PayPal',
                'img_name' => 'PayPal.svg'
            ],
            [
                'name' => 'Klarna',
                'img_name' => 'Klarna.svg'
            ],
            [
                'name' => 'Maestro',
                'img_name' => 'Maestro.svg'
            ],
            [
                'name' => 'Mastercard',
                'img_name' => 'Mastercard.svg'
            ],
            [
                'name' => 'Bonifico',
                'img_name' => 'Sepa.svg'
            ],
            [
                'name' => 'Stripe',
                'img_name' => 'Stripe.svg'
            ],
            [
                'name' => 'Visa',
                'img_name' => 'Visa.svg'
            ],
        ];

        foreach ($pay_methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
