<?php

namespace Database\Seeders;

use App\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        for($i=0; $i<8; $i++) {
            Customer::factory()->create();
        }
    }
}