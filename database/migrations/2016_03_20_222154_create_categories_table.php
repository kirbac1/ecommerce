<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            // Nested-set columns. Named lft/rgt rather than kalnoy/nestedset's default
            // _lft/_rgt; the model overrides the getters to match.
            // Column names may be changed, but they *must* all exist and be modified
            // in the model.
            // Take a look at the model scaffold comments for details.
            // We add indexes on parent_id, lft, rgt columns by default.

            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->integer('lft')->unsigned()->nullable()->index();
            $table->integer('rgt')->unsigned()->nullable()->index();
            $table->integer('depth')->unsigned()->nullable();

            // Add needed columns here (f.ex: name, slug, path, etc.)
            $table->string('name');
            $table->string('slug');
            $table->boolean('deletable')->default(true);

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
        Schema::dropIfExists('categories');
    }

}
