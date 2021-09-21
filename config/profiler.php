<?php

return [
    'debugbar' => [
        'enabled' => env('DEBUGBAR_ENABLED', true),
        'exclude' => [
            'telescope*',
            'horizon*'
        ],
        'storage' => [
            'enabled'    => true,
            'driver'     => 'file', // redis, file, pdo, custom
            'path'       => null, // For file driver
            'connection' => null,   // Leave null for default connection (Redis/PDO)
            'provider'   => '' // Instance of StorageInterface for custom driver
        ],
        'include_vendors' => true,

        'capture_ajax' => true,
        'add_ajax_timing' => false,

        'error_handler' => true,
        'collectors' => [
            'ripana.query' => true,
            'phpinfo' => true,  // Php version
            'messages' => true,  // Messages
            'time' => true,  // Time Datalogger
            'memory' => true,  // Memory usage
            'exceptions' => true,  // Exception displayer
            'log' => true,  // Logs from Monolog (merged in messages if enabled)
            'db' => true,  // Show database (PDO) queries and bindings
            'views' => true,  // Views with their data
            'route' => true,  // Current route information
            'auth' => false, // Display TeraBlaze authentication status
            'gate' => true,  // Display TeraBlaze Gate checks
            'session' => true,  // Display session data
            'symfony_request' => true,  // Only one can be enabled..
            'mail' => true,  // Catch mail messages
            'terablaze' => true, // TeraBlaze version and environment
            'events' => false, // All events fired
            'request' => true, // Regular or special Symfony request logger
            'logs' => false, // Add the latest log messages
            'files' => false, // Show the included files
            'config' => false, // Display config settings
            'cache' => false, // Display cache events
            'models' => true,  // Display models
            'livewire' => true,  // Display Livewire (when available)
        ],
    ],
];
