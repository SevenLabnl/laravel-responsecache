<?php

namespace SevenLab\ResponseCache;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

class ResponseCache
{
    /**
     * Remove items with specified tag from the cache.
     *
     * @param  array|mixed  $tags
     * @return bool
     */
    public function forget($tags): bool
    {
        return Cache::tags($tags)->flush();
    }

    /**
     * @deprecated Use the new clear method, this is just an alias.
     */
    public function flush(): bool
    {
        return $this->clear();
    }

    public function clear(): bool
    {
        return Cache::tags(
            config('responsecache.tag')
        )->flush();
    }
}
