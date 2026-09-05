<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Make every soft-delete column nullable.
 *
 * These five tables declared `deleted_at` as a plain NOT NULL timestamp. A
 * soft-delete column has to be nullable — "not deleted" is precisely NULL —
 * so inserting into any of them fails.
 *
 * It went unnoticed for a decade because the Laravel 5.2 config ran MySQL with
 * strict mode off, which silently coerced the missing value into the zero
 * date. Strict mode is on by default now, and rather than turning it back off
 * and hiding this class of bug, the schema is corrected.
 *
 * Raw SQL rather than Schema::table()->change(): `change()` needs the full
 * column definition restated and would rewrite attributes this migration has
 * no business touching.
 */
return new class extends Migration
{
    /** @var string[] */
    private array $tables = [
        'newsletter_customer',
        'newsletter_group',
        'newsletter_groups',
        'newsletters',
        'warehouses',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `deleted_at` TIMESTAMP NULL DEFAULT NULL");

            // Rows written under the old schema hold the zero date rather than
            // NULL, which would read back as "deleted".
            DB::table($table)->where('deleted_at', '0000-00-00 00:00:00')->update(['deleted_at' => null]);
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `deleted_at` TIMESTAMP NOT NULL");
        }
    }
};
