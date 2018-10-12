<?php

namespace SevenLab\ResponseCache\Middleware;

use Closure;

class DoNotCacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->attributes->add(['responsecache.doNotCache' => true]);

        return $next($request);
    }
}
