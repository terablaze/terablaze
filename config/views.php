<?php

use Terablaze\View\Engine\BasicEngine;
use Terablaze\View\Engine\LiteralEngine;
use Terablaze\View\Engine\PhaEngine;
use Terablaze\View\Engine\PhpEngine;

return [
    /*
    |--------------------------------------------------------------------------
    | Template Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Terablaze view path has already been registered for you.
    |
    */

    'paths' => [
        baseDir('src/App/resources/views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cached Template Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'cache_path' => env(
        'VIEW_COMPILED_PATH',
        storageDir('cache/' . kernel()->getEnvironment() . '/views')
    ),

    'should_cache' => true,

    'engines' => [
        'basic.php' => BasicEngine::class,
        'literal.php' => LiteralEngine::class,
        'php' => PhpEngine::class,
        'pha' => PhaEngine::class,
        'svg' => LiteralEngine::class,
    ]
];
