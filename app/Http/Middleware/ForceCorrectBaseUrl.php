<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceCorrectBaseUrl
{
    /**
     * Force URL generation to use scheme + host only (no /public or base path).
     * Fixes URLs like https://domain.com/public/fr → https://domain.com/fr
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rootUrl = $request->getSchemeAndHttpHost();

        URL::forceRootUrl($rootUrl);

        return $next($request);
    }
}
