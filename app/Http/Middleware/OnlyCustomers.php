<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;

class OnlyCustomers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user !== null) {
            if ($user->isCustomer) {
                return $next($request);
            }  else {
                App::abort(401, 'Unauthorized (not registered).');
                return false;
            }
        } else {
            return redirect('/webstore');
        }
    }
}
