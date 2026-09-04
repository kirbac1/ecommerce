<?php

namespace Database\Seeders;

use App\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        for($i=0; $i<2; $i++) {
            Warehouse::factory()->create();
        }
    }
}