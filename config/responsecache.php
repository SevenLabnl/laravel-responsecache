<?php

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
