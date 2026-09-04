<?php

namespace database\seeds;

use App\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        for($i=0; $i<2; $i++) {
            factory(Warehouse::class)->create();
        }
    }
}