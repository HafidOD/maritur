<?php

namespace App\Http\Middleware;

use Closure;

class LoginAffiliate
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
        $domain = $request->root();
        // echo $domain;
        $domain = str_replace('https://www.', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('http://www.', '', $domain);
        $domain = str_replace('http://', '', $domain);
        // echo $domain;
        $af = \App\Affiliate::where(['domain'=>$domain,'status'=>\App\Affiliate::STATUS_ACTIVE])->first();
        if($domain=='127.0.0.1:8000' || $af==null) \SC::$affiliateId = $_GET['af'] ?? 1;
        else \SC::$affiliateId = $af->id;
        config(['seotools.meta.defaults.title'=>\SC::getAffiliate()->name]);
        return $next($request);
    }
}
