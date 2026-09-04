<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // Model lifecycle hooks: password hashing, generated ticket/RMA codes,
    // category cache flushing.
    App\Providers\EventServiceProvider::class,
    // Route model bindings, including the eager-loads the API depends on.
    App\Providers\RouteServiceProvider::class,
];
