<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 9);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('subject');
            $table->enum('department', ['technical', 'bug', 'improvement']);
            $table->enum('status', ['open', 'closed', 'awaiting_response', 'pending_close'])->default('open');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        Schema::create('ticket_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('thread_id')->unsigned()->nullable();
            $table->foreign('thread_id')->references('id')->on('tickets')->onUpdate('cascade');
            $table->boolean('sentBySupport')->default(false);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('content', 3000);
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
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
    }
}
