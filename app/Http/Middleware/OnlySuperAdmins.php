<?php

namespace App\Http\Middleware;

use App;
use Closure;

class OnlySuperAdmins
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
            if ($user->isSuperAdmin) {
                return $next($request);
            }  else {
                return redirect('/admin/login')
                    ->with('error', 'That account is not a super administrator.');
            }
        } else {
            //App::abort(401, 'Unauthorized (not logged in).');
            return redirect('/admin/login');
        }
    }
}
