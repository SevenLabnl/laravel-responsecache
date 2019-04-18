<?php

namespace SevenLab\ResponseCache\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SevenLab\ResponseCache\ResponseSerializer;

class CacheResponse
{
    protected $responseSerializer;

    public function __construct(ResponseSerializer $responseSerializer)
    {
        $this->responseSerializer = $responseSerializer;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $minutes
     * @return mixed
     */
    public function handle($request, Closure $next, int $minutes = 60 * 24 * 7)
    {
        if ($this->shouldCache($request)) {
            $route = $request->route();
            $user  = $request->user();

            if ($route && $request->isMethod('get')) {
                $routeName = $route->getName();
                $routeAction = $route->getActionName();
                $routeTag = empty($routeName) ? $routeAction : $routeName;

                $cacheStore = Cache::tags([
                    config('responsecache.tag'),
                    $routeTag,
                ]);

                $key = isset($user, $user->id) ? $user->id : '?';
                $key = md5(
                    sprintf('%s-%s', $key, $request->getRequestUri())
                );

                if ($cacheStore->has($key)) {
                    return $this->responseSerializer->unserialize($cacheStore->get($key));
                } else {
                    $response = $next($request);

                    if ($response->isSuccessful()) {
                        $response->header('X-Cached-On', Carbon::now('UTC')->format('D, d M Y H:i:s').' GMT');
                        $cacheStore->put($key, $this->responseSerializer->serialize($response), now()->addMinutes($minutes));
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
