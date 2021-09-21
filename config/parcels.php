<?php

return [
    TeraBlaze\Core\CoreParcel::class => ['all' => true],
    TeraBlaze\Log\LogParcel::class => ['all' => true],
    TeraBlaze\Routing\RoutingParcel::class => ['all' => true],
//    TeraBlaze\Cache\CacheParcel::class => ['all' => true],
//    TeraBlaze\Session\SessionParcel::class => ['all' => true],
//    TeraBlaze\Database\DatabaseParcel::class => ['all' => true],
    TeraBlaze\Profiler\ProfilerParcel::class => ['dev' => true],
    TeraBlaze\Filesystem\FilesystemParcel::class => ['all' => true],
    TeraBlaze\View\ViewParcel::class => ['all' => true],
    App\App::class => ['all' => true],
];
