<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxedAndUntaxedTotalFieldForInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function(Blueprint $table) {
            $table->decimal('taxed_total')->nullable();
            $table->decimal('untaxed_total')->nullable();
            $table->decimal('taxes_total')->nullable();
            $table->decimal('costs_total')->nullable();
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->decimal('taxed_total')->nullable();
            $table->decimal('untaxed_total')->nullable();
            $table->decimal('taxes_total')->nullable();
            $table->decimal('costs_total')->nullable();
        });
        Schema::table('proformas', function(Blueprint $table) {
            $table->decimal('taxed_total')->nullable();
            $table->decimal('untaxed_total')->nullable();
            $table->decimal('taxes_total')->nullable();
            $table->decimal('costs_total')->nullable();
        });
        Schema::table('returns', function(Blueprint $table) {
            $table->decimal('taxed_total')->nullable();
            $table->decimal('untaxed_total')->nullable();
            $table->decimal('taxes_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns', function(Blueprint $table) {
            $table->dropColumn('taxed_total');
            $table->dropColumn('untaxed_total');
            $table->dropColumn('taxes_total');
        });
        Schema::table('proformas', function(Blueprint $table) {
            $table->dropColumn('taxed_total');
            $table->dropColumn('untaxed_total');
            $table->dropColumn('taxes_total');
            $table->dropColumn('costs_total');
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->dropColumn('taxed_total');
            $table->dropColumn('untaxed_total');
            $table->dropColumn('taxes_total');
            $table->dropColumn('costs_total');
        });
        Schema::table('invoices', function(Blueprint $table) {
            $table->dropColumn('taxed_total');
            $table->dropColumn('untaxed_total');
            $table->dropColumn('taxes_total');
            $table->dropColumn('costs_total');
        });
    }
}
