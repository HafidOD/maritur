<?php

namespace App\Http\Middleware;

use Closure;

class AdminLoginAffiliate
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
        \SC::$affiliateId = \Auth::user()->affiliateId;
        return $next($request);
    }
}
