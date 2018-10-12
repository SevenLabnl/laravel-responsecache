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

TODO

## Credits

- [Joey Houtenbos](https://github.com/JoeyHoutenbos)
- [Freek Van der Herten](https://github.com/freekmurze)
