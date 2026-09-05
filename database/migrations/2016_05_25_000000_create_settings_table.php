<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Store settings as key/value rows.
 *
 * Originally extended arcanedev/settings' migration base and took its table
 * name from that package's config. The package has no PHP 8 release and was
 * replaced by App\Support\Settings, so this is now a plain migration against
 * the same table shape.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->text('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
