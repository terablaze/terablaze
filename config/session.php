<?php

use TeraBlaze\Support\StringMethods;

return [

    'driver' => env('SESSION_DRIVER', 'cache'),

    'expire' => env('SESSION_EXPIRE', 10800),

    /*
     * Determines the Session storage to use for csrf
     * Can be either "session" or "flash"
     */
    'csrf_guard' => "session",

    'expire_on_close' => false,

    'encrypt' => false,

    'files' => storageDir('sessions'),

    'connection' => env('SESSION_CONNECTION', null),

    'table' => 'sessions',

    'cache' => env('SESSION_STORE', 'file'),

    'lottery' => [2, 100],

    'cache_limiter' => 'nocache',

    'last_modified' => null,

    'persistent' => false,

    'cookie' => [
        'name' => env(
            'SESSION_COOKIE',
            StringMethods::slug(env('APP_NAME', 'terablaze'), '_') . '_session'
        ),

        'path' => '/',

        'domain' => env('SESSION_DOMAIN', null),

        'secure' => env('SESSION_SECURE_COOKIE'),

        'http_only' => true,

        'same_site' => 'lax',
    ],

    'non_locking' => true,

];
