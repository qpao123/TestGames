<?php

namespace App\Http\Middleware;

use Closure;

class LoginCheck
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('username') != 'zx') {
            return redirect('user/login');
        }

        return $next($request);
    }
}
