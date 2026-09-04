<?php

namespace App\Http\Middleware;

use App;
use Closure;

class OnlyAdmins
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
        $user = $request->user();
        if ($user !== null) {
            if ($user->isAdmin) {
                return $next($request);
            }  else {
                App::abort(401, 'Unauthorized (not Admin).');
                return false;
            }
        } else {
            return redirect('/admin/login');
        }
    }
}
