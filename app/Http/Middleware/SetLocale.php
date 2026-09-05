<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Decides which language to render in.
 *
 * Previously every controller action called App::setLocale($user->language)
 * itself -- 45 times in AdminController alone -- which meant the storefront had
 * no locale handling at all (guests always got the fallback), and a language
 * chosen in the UI was immediately overwritten on the next request.
 *
 * Order of preference:
 *   1. an explicit choice held in the session (what the switcher sets)
 *   2. the signed-in account's `language` column
 *   3. the application default
 */
class SetLocale
{
    /**
     * Languages the app ships translations for.
     *
     * @var array<string, string>
     */
    public const SUPPORTED = [
        'en' => 'English',
        'fi' => 'Suomi',
        'it' => 'Italiano',
    ];

    public static function isSupported(?string $locale): bool
    {
        return $locale !== null && array_key_exists($locale, self::SUPPORTED);
    }

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($this->resolve($request));

        return $next($request);
    }

    private function resolve(Request $request): string
    {
        $chosen = $request->session()->get('locale');

        if (self::isSupported($chosen)) {
            return $chosen;
        }

        foreach (['web', 'customers'] as $guard) {
            $user = Auth::guard($guard)->user();

            if ($user && self::isSupported($user->language ?? null)) {
                return $user->language;
            }
        }

        return config('app.locale');
    }
}
