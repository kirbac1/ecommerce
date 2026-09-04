<?php

namespace Database\Seeders;

use App\CustomerGroup;
use Illuminate\Database\Seeder;

class CustomerGroupSeeder extends Seeder
{
    public function run()
    {
        CustomerGroup::create([
            'name' => '0%',
            'discountPercent' => 0
        ]);
        CustomerGroup::create([
            'name' => '0%',
            'discountPercent' => 50
        ]);
        CustomerGroup::create([
            'name' => '0%',
            'discountPercent' => 100
        ]);
    }
}