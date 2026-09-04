<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::statement('ALTER TABLE `categories` ADD FULLTEXT full(name)');
//        DB::statement('ALTER TABLE `customers` ADD FULLTEXT full(name, surname, email1, email2, phone, mobile, vatid, taxid)');
//        DB::statement('ALTER TABLE `customer_groups` ADD FULLTEXT full(name)');
//        DB::statement('ALTER TABLE `discounts` ADD FULLTEXT full(name)');
//        DB::statement('ALTER TABLE `invoices` ADD FULLTEXT full(company, name, surname, email1, email2, phone, mobile, vatid, taxid, street1, street2, city, state, zipcode, country)');
//        DB::statement('ALTER TABLE `manufacturers` ADD FULLTEXT full(name)');
//        DB::statement('ALTER TABLE `measure_units` ADD FULLTEXT full(name)');
//        DB::statement('ALTER TABLE `orders` ADD FULLTEXT full(company, name, surname, email1, email2, phone, mobile, vatid, taxid, street1, street2, city, state, zipcode, country)');
//        DB::statement('ALTER TABLE `payments` ADD FULLTEXT full(transaction_id)');
//        DB::statement('ALTER TABLE `products` ADD FULLTEXT full(name, sku, barcode)');
//        DB::statement('ALTER TABLE `proformas` ADD FULLTEXT full(company, name, surname, email1, email2, phone, mobile, vatid, taxid, street1, street2, city, state, zipcode, country)');
//        DB::statement('ALTER TABLE `returns` ADD FULLTEXT full(rma)');
//        DB::statement('ALTER TABLE `tickets` ADD FULLTEXT full(subject)');
//        DB::statement('ALTER TABLE `users` ADD FULLTEXT full(name, surname, email)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement('ALTER TABLE `categories` DROP INDEX full');
//        DB::statement('ALTER TABLE `customers` DROP INDEX full');
//        DB::statement('ALTER TABLE `customer_groups` DROP INDEX full');
//        DB::statement('ALTER TABLE `discounts` DROP INDEX full');
//        DB::statement('ALTER TABLE `invoices` DROP INDEX full');
//        DB::statement('ALTER TABLE `manufacturers` DROP INDEX full');
//        DB::statement('ALTER TABLE `measure_units` DROP INDEX full');
//        DB::statement('ALTER TABLE `orders` DROP INDEX full');
//        DB::statement('ALTER TABLE `payments` DROP INDEX full');
//        DB::statement('ALTER TABLE `products` DROP INDEX full');
//        DB::statement('ALTER TABLE `returns` DROP INDEX full');
//        DB::statement('ALTER TABLE `tickets` DROP INDEX full');
//        DB::statement('ALTER TABLE `users` DROP INDEX full');
    }
}
