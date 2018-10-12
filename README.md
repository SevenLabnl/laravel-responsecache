# Cache responses

TODO: Introduction

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

    'tag' => 'responsecache',

];
```

And finally you should install the provided middlewares in the HTTP kernel (`app/Http/Kernel.php`). 


```php
...

protected $middlewareGroups = [
   'web' => [
       ...
       \SevenLab\ResponseCache\Middlewares\CacheResponse::class,
   ],

...
```

## Usage

TODO
