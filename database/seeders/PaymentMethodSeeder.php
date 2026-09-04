<?php

namespace Database\Seeders;

use App\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            'Credit Card',
            'Bank Transfer',
            'Cash on Delivery',
            'Cash',
            'PayPal',
            'Check',
            'Bitcoin',
        ];

        foreach($methods as $method)
        {
            PaymentMethod::create([
                'name' => $method
            ]);
        }
    }
}