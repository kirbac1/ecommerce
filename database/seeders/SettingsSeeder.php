<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Support\Settings as Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        Setting::set('vat', 14);
        Setting::set('store_name', 'Ugur Bakkal');
        Setting::set('store_address_1', 'Itäkeskus');
        Setting::set('store_address_2', 'Kastelholmantie 2');
        Setting::set('store_address_3', '00900 Helsinki');
        Setting::set('store_vatid', 'FI32993-6');
        Setting::set('store_iban', 'FI2112345600000785');
        Setting::set('store_bic', '');
        Setting::set('store_email', 'info@example.com');
        Setting::set('store_url', 'http://localhost:8000');
        Setting::set('store_telephone', '097531022');
        Setting::set('store_mobile', '0504319997');
        Setting::set('store_motto', 'Aina tuoretta, aina edullista');
        Setting::set('ecommerce_title', 'Ugur Bakkal WebStore');

        // set() only stages the values -- they are not persisted until save().
        Setting::save();
    }
}