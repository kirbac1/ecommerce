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
        $user = Auth::guard('customers')->user();
        if ($user !== null) {
            if ($user->isCustomer) {
                return $next($request);
            }  else {
                return redirect('/account/login')
                    ->with('error', 'Please sign in to view your account.');
            }
        } else {
            return redirect('/account/login');
        }
    }
}
