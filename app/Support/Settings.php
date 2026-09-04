<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Key/value store settings, backed by the `settings` table.
 *
 * Replaces arcanedev/settings, which has no PHP 8 release. The API is kept
 * deliberately close to the old facade (get/set/all/forget/save) so the call
 * sites did not have to change, except that set() now writes immediately —
 * the old one staged values in memory and silently lost them unless save()
 * was called, which is how the seeded settings went missing.
 */
class Settings
{
    /** @var array<string,mixed>|null in-request cache */
    private static $cache = null;

    /** All settings as a key => value map. */
    public static function all()
    {
        if (self::$cache === null) {
            self::$cache = DB::table('settings')->pluck('value', 'key')->all();
        }

        return self::$cache;
    }

    public static function get($key, $default = null)
    {
        $all = self::all();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function has($key)
    {
        return array_key_exists($key, self::all());
    }

    /** Write a setting. Unlike the old package, this persists straight away. */
    public static function set($key, $value = null)
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => (string) $value]
        );

        self::all();
        self::$cache[$key] = (string) $value;
    }

    public static function forget($key)
    {
        DB::table('settings')->where('key', $key)->delete();
        unset(self::$cache[$key]);
    }

    /**
     * No-op kept so existing callers still read correctly; set() already
     * persisted. Retained because seeders and controllers call it.
     */
    public static function save()
    {
        return true;
    }

    /** Drop the in-request cache (used by tests and after bulk writes). */
    public static function flush()
    {
        self::$cache = null;
    }
}
