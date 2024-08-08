<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogService
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $log = [
            'user' => $request->user()->name,
            'service' => $request->path(),
            'request_body' => $request->getContent(),
            'http_code_response' => $response->getStatusCode(),
            'response_body' => $response->getContent(),
            'ip' => $request->ip(),
        ];

        Log::info(json_encode($log));

        return $response;
    }
}
