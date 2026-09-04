<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(database\seeds\SettingsSeeder::class);
        $this->call(database\seeds\TypeSeeder::class);
        $this->call(database\seeds\MeasureunitSeeder::class);
        $this->call(database\seeds\PaymentMethodSeeder::class);
        $this->call(database\seeds\UserSeeder::class);
        $this->call(database\seeds\ManufacturerSeeder::class);
        $this->call(database\seeds\CategoriesSeeder::class);
        $this->call(database\seeds\WarehouseSeeder::class);
        $this->call(database\seeds\ProductSeeder::class);
        $this->call(database\seeds\CustomerGroupSeeder::class);
        $this->call(database\seeds\CustomerSeeder::class);
        $this->call(database\seeds\DiscountSeeder::class);
//        $this->call(database\seeds\ProformaSeeder::class);
        $this->call(database\seeds\TicketSeeder::class);
        // Needs customers, products and users above: builds 30 days of orders/invoices.
        $this->call(database\seeds\InvoiceSeeder::class);
    }
}
