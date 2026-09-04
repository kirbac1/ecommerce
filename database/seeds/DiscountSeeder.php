<?php

namespace database\seeds;

use App\Discount;
use App\Product;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /** Product name => [discount name, percent off]. */
    private $offers = [
        'Baklava tray 1kg'          => ['Viikon tarjous', 20],
        'Black tea 1kg'             => ['Talvitarjous', 15],
        'Dried apricots 500g'       => ['2 + 1 kampanja', 25],
        'Green olives in brine 700g'=> ['Kuukauden tuote', 10],
        'Sunflower oil 5lt'         => ['Paljousalennus', 12],
    ];

    public function run()
    {
        foreach ($this->offers as $productName => $offer) {
            $product = Product::where('name', $productName)->first();

            if (! $product) {
                continue;
            }

            list($name, $percent) = $offer;

            Discount::create([
                'name' => $name,
                'product_id' => $product->id,
                'valuePercent' => $percent,
                // Column is NOT NULL, so it always needs a value even for
                // percent-type discounts.
                'valueAmount' => 0,
                'type' => 'percent',
            ]);
        }
    }
}
