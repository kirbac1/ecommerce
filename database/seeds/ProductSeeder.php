<?php

namespace database\seeds;

use App\Category;
use App\Manufacturer;
use App\Measureunit;
use App\Product;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Demo catalogue: name, category, unit, cost price, sale price, VAT %, pack size.
     * Prices are in euros and deliberately give every line a positive margin so the
     * dashboard's profit figures are meaningful.
     */
    private $products = [
        ['Bulgur, coarse 1kg',        'Cereals',        'kg',  1.80,  2.95, 14, 1],
        ['Red lentils 1kg',           'Cereals',        'kg',  2.10,  3.45, 14, 1],
        ['Basmati rice 2kg',          'Cereals',        'kg',  3.90,  6.20, 14, 1],
        ['Couscous 500g',             'Cereals',        'pcs', 1.25,  2.10, 14, 6],
        ['Green olives in brine 700g','Canned Food',    'pcs', 2.60,  4.30, 14, 6],
        ['Chickpeas, canned 400g',    'Canned Food',    'pcs', 0.75,  1.35, 14, 12],
        ['Tomato paste 700g',         'Canned Food',    'pcs', 1.95,  3.25, 14, 6],
        ['White cheese 500g',         'Cheese',         'pcs', 4.20,  6.90, 14, 4],
        ['Kashkaval cheese 400g',     'Cheese',         'pcs', 5.10,  8.25, 14, 4],
        ['Dried apricots 500g',       'Dry Fruits',     'pcs', 3.40,  5.60, 14, 6],
        ['Sultana raisins 500g',      'Dry Fruits',     'pcs', 2.15,  3.60, 14, 6],
        ['Baklava tray 1kg',          'Confectionery',  'pcs', 8.50, 14.90, 14, 1],
        ['Turkish delight, rose 350g','Confectionery',  'pcs', 2.75,  4.60, 14, 6],
        ['Black tea 1kg',             'Drinks',         'kg',  6.30, 10.50, 14, 1],
        ['Ayran 250ml',               'Drinks',         'pcs', 0.45,  0.95, 14, 24],
        ['Sunflower oil 5lt',         'For baking',     'lt',  7.80, 11.90, 14, 1],
    ];

    public function run()
    {
        $catalogPath = public_path('catalog');

        foreach ($this->products as $row) {
            list($name, $categoryName, $unit, $basePrice, $priceEach, $tax, $qtyPerPack) = $row;

            $filename = self::slug($name) . '.png';
            PlaceholderImage::make($catalogPath . '/' . $filename, $name);

            Product::create([
                'name' => $name,
                'image' => $filename,
                'category_id' => self::categoryId($categoryName),
                'measureunit_id' => self::measureunitId($unit),
                'manufacturer_id' => self::manufacturerId(),
                'basePrice' => $basePrice,
                'priceEach' => $priceEach,
                'taxPercent' => $tax,
                'qtyPerPack' => $qtyPerPack,
                'sku' => 'SKU-' . str_pad(crc32($name) % 100000, 5, '0', STR_PAD_LEFT),
                'barcode' => (string) (6400000000000 + (crc32($name) % 1000000)),
                'visible' => true,
            ]);
        }
    }

    private static function slug($name)
    {
        return trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9]+/', '-', strtolower($name))), '-');
    }

    /** Look the category up by name, falling back to the first one that exists. */
    private static function categoryId($name)
    {
        $category = Category::where('name', $name)->first();

        return $category ? $category->id : Category::first()->id;
    }

    private static function measureunitId($name)
    {
        $unit = Measureunit::where('name', $name)->first();

        return $unit ? $unit->id : Measureunit::first()->id;
    }

    private static function manufacturerId()
    {
        $manufacturer = Manufacturer::orderByRaw('RAND()')->first();

        return $manufacturer ? $manufacturer->id : null;
    }

    /**
     * Seed the given connection from the given path.
     *
     * @param  string  $class
     * @return void
     */
    public function call($class, $extra = null) {
        $this->resolve($class)->run($extra);

        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<info>Seeded:</info> $class");
        }
    }
}
