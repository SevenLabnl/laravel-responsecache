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
        if ($this->shouldCache($request)) {
            $route = $request->route();
            $user  = $request->user();

            if ($route && $user && $request->isMethod('get')) {
                $routeName = $route->getName();
                $routeAction = $route->getActionName();
                $routeTag = empty($routeName) ? $routeAction : $routeName;

                $tags = [
                    config('responsecache.tag'),
                    $routeTag,
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

    private function shouldCache(Request $request)
    {
        return $this->isEnabled()
            && $this->isRunningInConsole() === false
            && $this->doNotCache($request) === false;
    }

    private function isEnabled(): bool
    {
        return config('responsecache.enabled');
    }

    private function isRunningInConsole(): bool
    {
        if (app()->environment('testing')) {
            return false;
        }

        return app()->runningInConsole();
    }

    private function doNotCache(Request $request): bool
    {
        return $request->attributes->has('responsecache.doNotCache');
    }
}
