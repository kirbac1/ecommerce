<?php

namespace Database\Seeders;

use App\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // A known storefront account, so returning-customer login can actually
        // be used and demonstrated. Every generated customer below has a null
        // password and therefore cannot sign in at all.
        //
        // Flagged demo, so the session is read-only like the staff logins.
        Customer::factory()->create([
            'name' => 'Demo',
            'surname' => 'Customer',
            'company' => null,
            'type' => 'person',
            'email1' => 'customer@example.com',
            'password' => 'test',
            'enabled' => true,
            'demo' => true,
        ]);

        for ($i = 0; $i < 8; $i++) {
            Customer::factory()->create();
        }
    }
}
