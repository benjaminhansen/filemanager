<?php

namespace App\Http\Middleware;

use Closure;
use App\Support\Helpers;

class IsAcceptableNetwork
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
        if(!Helpers::isAcceptableNetwork(env('ACCEPTABLE_NETWORK_CIDR'))) {
            session()->flush();
            auth()->logout();
            return redirect('/');
        }
        return $next($request);
    }
}
