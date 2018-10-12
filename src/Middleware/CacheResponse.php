<?php

namespace SevenLab\ResponseCache\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  DateTimeInterface|DateInterval|float|int  $minutes
     * @return mixed
     */
    public function handle($request, Closure $next, $minutes = 1440)
    {
        $route = $request->route();
        $user  = $request->user();

        if ($route && $user && $request->isMethod('get')) {
            $tags = [
                config('responsecache.tag'),
                $route->getName(),
            ];
            $key = md5(
                sprintf('%s-%s', $user->id, $request->getRequestUri())
            );

            return Cache::tags($tags)->remember($key, $minutes, function () use ($request, $next) {
                $response = $next($request);
                $response->header('X-Cached-On', Carbon::now('UTC')->format('D, d M Y H:i:s').' GMT');

                return $response;
            });
        }

        return $next($request);
    }
}
