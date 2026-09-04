<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function(Blueprint $table) {
            $table->increments('id');
            $table->string('rma', 5)->nullable();
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->enum('entityType', ['person', 'company']);
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('company')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('vatid');
            $table->string('taxid');
            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->string('notes')->nullable();
            $table->decimal('discount')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        Schema::create('return_product', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('return_id')->unsigned();
            $table->foreign('return_id')->references('id')->on('returns');
            $table->decimal('quantity');
            $table->decimal('priceEach')->nullable();
            $table->decimal('taxPercent');
        });

        Schema::create('invoice_return', function(Blueprint $table) {
            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('cascade');
            $table->integer('return_id')->unsigned();
            $table->foreign('return_id')->references('id')->on('returns')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_return');
        Schema::dropIfExists('return_product');
        Schema::dropIfExists('returns');
    }
}
