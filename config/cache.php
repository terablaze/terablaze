<?php

return [
    'default' => 'file',

    'stores' => [
//        'memory' => [
//            'type' => 'memory',
//            'seconds' => 31536000,
//        ],

        'file' => [
            'type' => 'file',
            'ttl' => 31536000,
            'data_type' => 'json'
        ],

        'memcached' => [
            'type' => 'memcache',
            'host' => '127.0.0.1',
            'port' => 11211,
            'seconds' => 12,
            'prefix' => 'dlave_'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', \TeraBlaze\Support\StringMethods::slug(env('APP_NAME', 'terablaze'), '_').'_cache'),
];