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
                App::abort(401, 'Unauthorized (not user).');
                return false;
            }
        } else {
            return redirect('/admin/login');
        }
    }
}
