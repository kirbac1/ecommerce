<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Read-only demo mode
    |--------------------------------------------------------------------------
    |
    | With this on, the whole site is read-only for everyone: the app renders
    | and navigates normally, but nothing can be written to the database. It is
    | what makes it safe to publish the demo credentials.
    |
    | Individual accounts can also be flagged `demo` in the users table, which
    | applies the same restriction to just those logins while leaving the rest
    | of the site writable.
    |
    */

    'enabled' => env('DEMO_MODE', false),

];
