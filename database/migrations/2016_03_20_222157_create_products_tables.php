<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('manufacturer_id')->unsigned()->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onUpdate('cascade');
            $table->string('image')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->string('name');
            $table->boolean('visible')->default(true);
            $table->string('sku')->nullable()->index();
            $table->string('barcode')->nullable();
            $table->decimal('qtyPerPack')->default(1);
            $table->decimal('basePrice')->nullable();
            $table->decimal('taxPercent')->default(24);
            $table->decimal('profitPercent')->default(13);
            $table->integer('measureunit_id')->unsigned()->nullable();
            $table->foreign('measureunit_id')->references('id')->on('measureunits')->onUpdate('cascade');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
