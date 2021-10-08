<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class InOffice
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
        $environment = env('APP_ENV');
        if ($environment === 'production') {
            $officeIpAddresses = Config::get('office-ip-addresses.ipAddresses', []);
            if (!in_array($request->ip(), $officeIpAddresses)) {
                return response('Unauthorized.', 401);
            }
        }
        return $next($request);
    }
}
