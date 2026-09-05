<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Makes the app read-only so demo credentials can be published safely.
 *
 * Active when either `DEMO_MODE=true` (the whole site is read-only, whoever is
 * looking) or the signed-in user is flagged `demo` in the database.
 *
 * Blocking on the HTTP verb alone is not enough here. Two things break that
 * assumption in this app:
 *
 *   - logging in is a POST, and a demo that cannot log in is useless, so the
 *     auth endpoints are allowed through;
 *   - several routes mutate on a GET (`convertToInvoice` and friends), so they
 *     are named explicitly rather than trusted for being GETs.
 */
class PreventDemoWrites
{
    /** Verbs that never change state. */
    private const READ_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    /**
     * Write endpoints a demo visitor still needs.
     *
     * @var string[]
     */
    private const ALLOWED_WRITES = [
        'admin/login',
        'admin/logout',
        'account/login',
        'account/logout',
        'cashier/login',
        'cashier/logout',
        'api/v3/customer/login',
        'api/v3/customer/logout',
    ];

    /**
     * GET routes that write. Matched as path segments, so the id in the middle
     * of e.g. `api/v3/orders/12/convertToInvoice` does not matter.
     *
     * @var string[]
     */
    private const MUTATING_GETS = [
        'convertToInvoice',
        'convertToReceipt',
        'convertToOrder',
        'productmigration/export',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->readOnly()) {
            return $next($request);
        }

        if ($this->isAllowed($request)) {
            return $next($request);
        }

        if ($this->writes($request)) {
            return $this->refuse($request);
        }

        return $next($request);
    }

    /** Is this request subject to the read-only rules? */
    private function readOnly(): bool
    {
        if (config('demo.enabled')) {
            return true;
        }

        foreach (['web', 'customers'] as $guard) {
            $user = Auth::guard($guard)->user();

            if ($user && ! empty($user->demo)) {
                return true;
            }
        }

        return false;
    }

    private function isAllowed(Request $request): bool
    {
        foreach (self::ALLOWED_WRITES as $path) {
            if ($request->is($path)) {
                return true;
            }
        }

        return false;
    }

    private function writes(Request $request): bool
    {
        if (! in_array($request->method(), self::READ_METHODS, true)) {
            return true;
        }

        foreach (self::MUTATING_GETS as $segment) {
            if ($request->is('*' . $segment) || $request->is('*' . $segment . '/*')) {
                return true;
            }
        }

        return false;
    }

    private function refuse(Request $request): Response
    {
        $message = __('This is a read-only demo. Changes are not saved.');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'error' => 'demo_read_only',
                'message' => $message,
            ], 403);
        }

        return back()->with('demo_error', $message)->setStatusCode(303);
    }
}
