<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use function env;
use function json_encode;

class LogRequest
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
        $isEnable = env('IS_ENABLE_LOG_REQUEST_MIDDLEWARE') !== null ? env('IS_ENABLE_LOG_REQUEST_MIDDLEWARE') : false;
        if ($isEnable) {
            Log::info("audit trail #" . session()->getId() . " : (" . url()->full() . ") - " . json_encode($request->all()) . " ## header - " . json_encode($request->header('User-Agent')));
        }

        return $next($request);
    }
}
