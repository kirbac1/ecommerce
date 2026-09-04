<?php

namespace database\seeds;

use App\Customer;
use App\Proforma;
use App\Product;
use App\User;
use Illuminate\Database\Seeder;

class ProformaSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $customers = Customer::orderByRaw('RAND()')->take(5)->get();
        foreach($customers as $customer) {
            $proforma = new Proforma();
            $proforma->customer = $customer;
            $proforma->user = User::find(1);
            $proforma->company = $customer->company;
            $proforma->name = $customer->name;
            $proforma->surname = $customer->surname;
            $proforma->email1 = $customer->email1;
            $proforma->email2 = $customer->email2;
            $proforma->website = $customer->website;
            $proforma->phone = $customer->phone;
            $proforma->mobile = $customer->mobile;
            $proforma->vatid = $customer->vatid;
            $proforma->street1 = $customer->street1;
            $proforma->street2 = $customer->street2;
            $proforma->city = $customer->city;
            $proforma->state = $customer->state;
            $proforma->zipcode = $customer->zipcode;
            $proforma->country = $customer->country;
            $proforma->save();
            $proforma->addProduct($products[round(rand(0, count($products)-1))], 1.00, round(rand(1,100)));
            $proforma->addProduct($products[round(rand(0, count($products)-1))], 1.00, round(rand(1,100)));
            $proforma->addProduct($products[round(rand(0, count($products)-1))], 1.00, round(rand(1,100)));
        }
    }
}