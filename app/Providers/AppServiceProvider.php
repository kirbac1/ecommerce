<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // This app targets PHP 5.5/7.0, where count(null) returned 0 and reading a
        // property off null was silent. PHP 7.2+ made those a warning/notice, and
        // Laravel 5.2 escalates anything in error_reporting() into an exception --
        // which breaks Eloquent itself (Builder::applyScopes does count($query->wheres)).
        // Restore the error levels this codebase was written against.
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
