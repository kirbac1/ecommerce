<?php

namespace App\Providers;

use Arcanedev\Settings\Facades\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // The PHP 5.6/7.0 error_reporting shim that used to live here is gone.
        // It existed because Laravel 5.2's Eloquent called count() on a null,
        // which PHP 7.2 made a warning that the framework escalated into an
        // exception. On Laravel 12 that code is gone, so warnings are left
        // switched on and the few places that relied on the old leniency were
        // fixed instead.

        // The storefront chrome needs the shop's own name for its <title> and
        // share metadata. Read once here rather than in every view. Wrapped
        // because the settings store is unavailable during early console
        // commands (migrate on a fresh database, for one).
        View::share('storeName', $this->storeName());
    }

    private function storeName(): string
    {
        try {
            $name = Setting::get('store_name');
        } catch (\Throwable $e) {
            $name = null;
        }

        return $name ?: config('app.name');
    }

    public function register(): void
    {
        //
    }
}
