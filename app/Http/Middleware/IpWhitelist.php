<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpWhitelist
{
    /**
     * Lista dozwolonych adresów IP.
     *
     * @var array
     */
    protected $whitelist = [
        '123.456.789.101', // Przykładowy adres IP
    ];

    /**
     * Obsługa żądania.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), $this->whitelist)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
