<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $userType = ['cashier', 'shippings'];
    shuffle($userType); $userType = $userType[0];
    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'enabled' => true,
        'type' => $userType,
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    $company_name = null;
    $customerType = ['company', 'person'];
    shuffle($customerType); $customerType = $customerType[0];
    if ($customerType === 'company') {
        $company_name = $faker->company;
    }

    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'company' => $company_name,
        'type' => $customerType,
        'email1' => $faker->safeEmail,
        'email2' => $faker->safeEmail,
        'website' => $faker->url,
        'phone' => $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'vatid' => 'VAT' . randomChars(5),
        'taxid' => 'TAX' . randomChars(5),
        'street1' => $faker->streetAddress,
        'street2' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->city,
        'zipcode' => $faker->postcode,
        'country' => $faker->country,
        'notes' => $faker->paragraphs(1, true),
        'customer_group_id' => round(rand(1, 3)),
        'enabled' => true,
    ];
});

$factory->define(App\Discount::class, function (Faker\Generator $faker, $product = null) {
    if (!$product) $product = \App\Product::orderByRaw('RAND()')->first();
    return [
        'name' => $faker->word,
        'product' => $product,
        'valuePercent' => rand(0,100),
        'type' => 'percent',
    ];
});

$factory->define(App\Manufacturer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    $categories = [1, 2];
    shuffle($categories); $category_id = $categories[0];
    $measureunit_id = \App\Measureunit::orderByRaw('RAND()')->first()->id;
    $basePrice = round(rand(10, 40), 1);

    return [
        'image' => null,
        'name' => $faker->word,
        'qtyPerPack' => round(rand(1, 6)),
        'manufacturer_id' => \App\Manufacturer::orderByRaw('RAND()')->first()->id,
        'barcode' => $faker->ean13,
        'sku' => $faker->isbn13,
        'category_id' => $category_id,
        'measureunit_id' => $measureunit_id,
        'basePrice' => $basePrice,
        'taxPercent' => round(rand(10, 40)),
        'priceEach' => round($basePrice * 1.30, 1),
    ];
});

$factory->define(App\Warehouse::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\Warehouse::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\CustomerGroup::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'discountPercent' => 0,
    ];
});

$factory->define(App\TicketThread::class, function(Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'subject' => $faker->paragraph(),
        'department' => 1,
        'status' => 'open',
    ];
});

$factory->define(App\TicketMessage::class, function(Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'content' => $faker->paragraph(4),
        'sentBySupport' => $faker->boolean(),
    ];
});

function randomChars($length = 5)
{
    return substr(str_shuffle("0123456789"), 0, $length);
}