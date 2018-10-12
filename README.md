[![Latest Version on Packagist](https://img.shields.io/packagist/v/7Lab/laravel-responsecache.svg?style=flat-square)](https://packagist.org/packages/7Lab/laravel-responsecache)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/7Lab/laravel-responsecache.svg?style=flat-square)](https://packagist.org/packages/7Lab/laravel-responsecache)

# Cache responses
This Laravel package can cache an entire response. By default it will cache all successful GET-requests for a week. This could potentially speed up the response quite considerably.

So the first time a request comes in the package will save the response before sending it to the users. When the same request comes in again we're not going through the entire application but just respond with the saved response.

The package is based on [spatie/laravel-responsecache](https://github.com/spatie/laravel-responsecache) but uses the defined [route names](https://laravel.com/docs/routing#named-routes) so the cached responses can easily be cleared without having to clear the whole cache.

## Installation
You can install the package via Composer:
```bash
composer require 7Lab/laravel-responsecache
```

The package will automatically register itself.

You can publish the config file with:
```bash
php artisan vendor:publish --provider="SevenLab\ResponseCache\ResponseCacheServiceProvider"
```

This is the contents of the published config file (`config/responsecache.php`):
```php
return [

    /*
     * Determine if the response cache middleware should be enabled.
     */
    'enabled' => env('RESPONSECACHE_ENABLED', true),

    /*
     * Specify the tag name that will be used for the cache.
     */
    'tag' => env('RESPONSECACHE_TAG', 'responsecache'),

];
```

And finally you should install the provided middlewares in the HTTP kernel (`app/Http/Kernel.php`). 
```php
...

protected $routeMiddleware = [
    ...
    'cacheResponse' => \SevenLab\ResponseCache\Middlewares\CacheResponse::class,
    'doNotCacheResponse' => \SevenLab\ResponseCache\Middlewares\DoNotCacheResponse::class,
];

...
```

## Usage
By default it will cache all successful GET-requests for a week. Logged in users will each have their own separate cache.

### Caching specific routes
When using the route middleware you can specify the number of minutes these routes should be cached:
```php
// cache this route for 5 minutes
Route::get('/my-special-snowflake', 'SnowflakeController@index')->middleware('cacheResponse:5');

// cache all these routes for 10 minutes
Route::group(function() {
   Route::get('/another-special-snowflake', 'AnotherSnowflakeController@index');
   
   Route::get('/yet-another-special-snowflake', 'YetAnotherSnowflakeController@index');
})->middleware('cacheResponse:10');
```

### Preventing a route from being cached
Requests can be ignored by using the `doNotCacheResponse`-middleware. This middleware [can be assigned to routes and controllers](http://laravel.com/docs/master/controllers#controller-middleware).

Using the middleware on a route:
```php
Route::get('/auth/logout', 'AuthController@getLogout')->name('auth.logout')->middleware('doNotCacheResponse');
```

Alternatively you can add the middleware to a controller:
```php
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('doNotCacheResponse', ['only' => ['getLogout']]);
    }
}
```

### Clearing specific routes
Specific routes can be cleared with:
```php
ResponseCache::forget(['auth.logout']);
```

The same can be accomplished by issuing this artisan command:
```bash
php artisan responsecache:forget auth.logout
```

### Clearing all routes
The entire cache can be cleared with:
```php
ResponseCache::clear();
```
This will clear everything from the cache store specified in the config-file (`config/cache.php`) with the tag specified in the responsecache-file (`config/responsecache.php`).

The same can be accomplished by issuing this artisan command:
```bash
php artisan responsecache:clear
```

## Credits
- [Joey Houtenbos](https://github.com/JoeyHoutenbos)
- [Freek Van der Herten](https://github.com/freekmurze)
