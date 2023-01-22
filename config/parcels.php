<?php

return [
    Terablaze\Core\CoreParcel::class => ['all' => true],
    Terablaze\Translation\TranslationParcel::class => ['all' => true],
    Terablaze\Log\LogParcel::class => ['all' => true],
    Terablaze\Routing\RoutingParcel::class => ['all' => true],
//    Terablaze\Cache\CacheParcel::class => ['all' => true],
//    Terablaze\Session\SessionParcel::class => ['all' => true],
//    Terablaze\Database\DatabaseParcel::class => ['all' => true],
    Terablaze\Profiler\ProfilerParcel::class => ['dev' => true],
    Terablaze\Filesystem\FilesystemParcel::class => ['all' => true],
    Terablaze\View\ViewParcel::class => ['all' => true],
    App\App::class => ['all' => true],
];
