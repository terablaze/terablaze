<?php

return [
    'debugbar' => [
        'enabled' => env('DEBUGBAR_ENABLED', true),

        'exclude' => [
            'telescope*',
            'horizon*'
        ],
        'storage' => [
            'enabled' => true,
            'driver' => 'file', // redis, file, pdo, custom
            'path' => null, // For file driver
            'connection' => null,   // Leave null for default connection (Redis/PDO)
            'provider' => '' // Instance of StorageInterface for custom driver
        ],
        'include_vendors' => true,

        'capture_ajax' => false,
        'add_ajax_timing' => false,

        'error_handler' => true,
        'collectors' => [
            'phpinfo' => true,  // Php version
            'messages' => true,  // Messages
            'time' => true,  // Time Datalogger
            'memory' => true,  // Memory usage
            'exceptions' => true,  // Exception displayer
            'log' => true,  // Logs from Monolog (merged in messages if enabled)
            'views' => true,  // Views with their data
            'route' => true,  // Current route information
            'auth' => false, // Display TeraBlaze authentication status
            'gate' => true,  // Display TeraBlaze Gate checks
            'session' => true,  // Display session data
            'mail' => true,  // Catch mail messages
            'terablaze' => true, // TeraBlaze version and environment
            'events' => true, // All events fired
            'request' => true, // Regular or special Symfony request logger
            'logs' => true, // Add the latest log messages
            'files' => false, // Show the included files
            'config' => true, // Display config settings
            'cache' => false, // Display cache events
            'db' => true,  // Show database (PDO) queries and bindings
            'models' => true,  // Display models
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Extra options
     |--------------------------------------------------------------------------
     |
     | Configure some DataCollectors
     |
     */

    'options' => [
        'auth' => [
            'show_name' => true,   // Also show the users name/email in the debugbar
        ],
        'db' => [
            'with_params' => true,   // Render SQL with the parameters substituted
            'backtrace' => true,   // Use a backtrace to find the origin of the query in your files.
            'backtrace_exclude_paths' => [],   // Paths to exclude from backtrace. (in addition to defaults)
            'timeline' => false,  // Add the queries to the timeline
            'duration_background' => true,   // Show shaded background on each query relative to how long it took to execute.
            'explain' => [                 // Show EXPLAIN output on queries
                'enabled' => false,
                'types' => ['SELECT'],     // Deprecated setting, is always only SELECT
            ],
            'hints' => false,    // Show hints for common mistakes
            'show_copy' => false,    // Show copy button next to the query
        ],
        'mail' => [
            'full_log' => false,
        ],
        'views' => [
            'timeline' => false,  // Add the views to the timeline (Experimental)
            'data' => true,    //Note: Can slow down the application, because the data can be quite large..
        ],
        'route' => [
            'label' => true,  // show complete route on bar
        ],
        'logs' => [
            'file' => null,
        ],
        'cache' => [
            'values' => true, // collect cache values
        ],
    ],
];

