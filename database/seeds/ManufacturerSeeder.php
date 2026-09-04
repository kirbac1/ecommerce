<?php

namespace database\seeds;

use App\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    public function run()
    {
        // Invented supplier names -- demo data only.
        $names = [
            'Anatolia Foods',
            'Aegean Trading',
            'Marmara Mills',
            'Levant Import',
            'Nordic Wholesale',
            'Bosphorus Deli',
        ];

        foreach ($names as $name) {
            Manufacturer::create(['name' => $name]);
        }
    }
}
