<?php

namespace SevenLab\ResponseCache;

use Illuminate\Support\Facades\Cache;

class ResponseCache
{
    public function __construct()
    {
        //
    }

    /**
     * @deprecated Use the new clear method, this is just an alias.
     */
    public function flush()
    {
        $this->clear();
    }

    public function clear()
    {
        Cache::tags(
            config('responsecache.tag')
        )->flush();
    }
}
