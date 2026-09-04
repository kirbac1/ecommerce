<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('updated_at');
            $table->timestamp('created_at');
            $table->timestamp('deleted_at');
        });
        Schema::create('product_warehouse', function(Blueprint $table) {
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade');
            $table->decimal('quantity');
            $table->timestamp('updated_at');
            $table->timestamp('created_at');
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
        Schema::dropIfExists('product_warehouse');
        Schema::dropIfExists('warehouses');
    }
}
