<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceNonWww
{
    public function handle(Request $request, Closure $next): Response
    {
        if (str_starts_with($request->getHost(), 'www.')) {
            $newUrl = $request->getScheme() . '://' . 
                      substr($request->getHost(), 4) . 
                      $request->getRequestUri();
            
            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}