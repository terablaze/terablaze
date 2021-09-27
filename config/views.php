<?php

use TeraBlaze\View\Engine\AdvancedEngine;
use TeraBlaze\View\Engine\BasicEngine;
use TeraBlaze\View\Engine\LiteralEngine;
use TeraBlaze\View\Engine\PhaEngine;
use TeraBlaze\View\Engine\PhpEngine;

return [
    /*
    |--------------------------------------------------------------------------
    | Template Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual TeraBlaze view path has already been registered for you.
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
        'advanced.php' => AdvancedEngine::class,
        'literal.php' => LiteralEngine::class,
        'php' => PhpEngine::class,
        'html' => PhaEngine::class,
        'pha.html' => PhaEngine::class,
        'svg' => LiteralEngine::class,
    ]
];
