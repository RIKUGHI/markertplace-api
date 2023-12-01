<?php

namespace App\Http\Middleware;

use App\Helper\Api;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->tokenCan('seller')) {
            return $next($request);
        }

        return Api::sendResponse(403, 'Forbidden', null);
    }
}
