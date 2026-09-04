<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Everything lives in web.php, including /api/v3. Those endpoints were
        // written against the session guard and the CSRF token the Vue pages
        // send, so they need the web middleware group, not the stateless api one.
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Read-only demo guard. It has to sit in the web group rather than the
        // global stack: global middleware runs before StartSession, so the
        // guard would not yet know who is signed in and every demo account
        // would sail straight through. Every route in this app is in the web
        // group, so coverage is the same.
        $middleware->web(append: [
            \App\Http\Middleware\PreventDemoWrites::class,
        ]);

        // Route-group aliases carried over from the 5.2 kernel.
        $middleware->group('admins', [
            \App\Http\Middleware\OnlyAdmins::class,
        ]);

        $middleware->group('superadmins', [
            \App\Http\Middleware\OnlySuperAdmins::class,
        ]);

        $middleware->group('users', [
            \App\Http\Middleware\OnlyUsers::class,
        ]);

        $middleware->group('customers', [
            \App\Http\Middleware\OnlyCustomers::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
