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
                // Signed in, but not an admin. Aborting here left the user stuck:
                // no link out, and the wrong session still active. Send them to the
                // admin login so they can sign in as someone who is.
                return redirect('/admin/login')
                    ->with('error', 'That account is not an administrator.');
            }
        } else {
            return redirect('/admin/login');
        }
    }
}
