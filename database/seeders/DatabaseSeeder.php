<?php

namespace Database\Seeders;

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
        $this->call(SettingsSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(MeasureunitSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ManufacturerSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerGroupSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(DiscountSeeder::class);
//        $this->call(ProformaSeeder::class);
        $this->call(TicketSeeder::class);
        // Needs customers, products and users above: builds 30 days of orders/invoices.
        $this->call(InvoiceSeeder::class);
    }
}
