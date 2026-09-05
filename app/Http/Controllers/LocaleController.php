<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the display language.
     *
     * Stored in the session rather than written to the account, so it works for
     * signed-out visitors and for the read-only demo logins alike. The account's
     * own `language` column stays the default for anyone who has not chosen.
     */
    public function switch(Request $request, string $locale)
    {
        if (! SetLocale::isSupported($locale)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return back();
    }
}
