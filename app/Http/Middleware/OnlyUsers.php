<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;

class OnlyUsers
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
            if ($user->isUser) {
                return $next($request);
            }  else {
                return redirect('/cashier/login')
                    ->with('error', 'That account cannot use the register.');
            }
        } else {
            return redirect('/admin/login');
        }
    }
}
