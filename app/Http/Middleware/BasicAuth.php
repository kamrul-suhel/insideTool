<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuth
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

        $AUTH_USER = config('auth.basic.user');
        $AUTH_PASS = config('auth.basic.pass');

        $user = $request->get('user');
        $pass = $request->get('pass');

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $has_supplied_credentials =
            !(empty($user) && empty($pass));

        $is_not_authenticated = ( !$has_supplied_credentials
            || $user!= $AUTH_USER
            || $pass!= $AUTH_PASS);

        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            dd('Not Authorised');
        }
        return $next($request);
    }
}
