<?php

namespace App\Http\Middleware;

use Closure;

class CanAccessDepartment
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
        if(!auth()->user()->canAccessDepartment($request->id)) {
            return redirect('dashboard');
        }
        return $next($request);
    }
}
