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
    public function handle($request, Closure $next, $minutes = 60 * 24 * 7)
    {
        if ($this->enabled($request)) {
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

                $cacheStore = Cache::tags($tags);

                if ($cacheStore->has($key)) {
                    return $cacheStore->get($key);
                } else {
                    $response = $next($request);

                    if ($response->isSuccessful()) {
                        $response->header('X-Cached-On', Carbon::now('UTC')->format('D, d M Y H:i:s').' GMT');
                        $cacheStore->put($key, $minutes, $response);
                    }

                    return $response;
                }
            }
        }

        return $next($request);
    }

    private function enabled(Request $request): bool
    {
        return config('responsecache.enabled') && $request->attributes->has('responsecache.doNotCache') === false;
    }
}
