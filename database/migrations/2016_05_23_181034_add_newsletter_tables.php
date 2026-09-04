<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewsletterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('language', 4)->default('fi');
            $table->string('content', 5000);
            $table->integer('fails')->unsigned()->nullable();
            $table->timestamp('launched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('scheduled_at');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });

        Schema::create('newsletter_groups', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });

        Schema::create('newsletter_group', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('newsletter_group_id')->unsigned()->nullable();
            $table->foreign('newsletter_group_id')->references('id')->on('newsletter_groups')->onUpdate('cascade');
            $table->integer('newsletter_id')->unsigned()->nullable();
            $table->foreign('newsletter_id')->references('id')->on('newsletters')->onUpdate('cascade');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });

        Schema::create('newsletter_customer', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('newsletter_group_id')->unsigned()->nullable();
            $table->foreign('newsletter_group_id')->references('id')->on('newsletter_groups')->onUpdate('cascade');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletter_customer');
        Schema::dropIfExists('newsletter_group');
        Schema::dropIfExists('newsletter_groups');
        Schema::dropIfExists('newsletters');
    }
}
