<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Marks accounts whose sessions must never write to the database, so their
 * credentials can be shared publicly. See App\Http\Middleware\PreventDemoWrites.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('demo')->default(false)->after('enabled');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('demo')->default(false)->after('enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('demo');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('demo');
        });
    }
};
