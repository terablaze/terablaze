<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => '',
        ],

        'public' => [
            'driver' => 'local',
            'root' => 'public',
            'url' => '',
            'visibility' => 'public',
        ],

//        's3' => [
//            'driver' => 's3',
//            'async' => '',
//            'key' => '',
//            'secret' => '',
//            'region' => '',
//            'bucket' => '',
//            'url' => '',
//            'endpoint' => '',
//        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Blaze command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */
    'links' => [
        publicDir('storage') => storageDir('public'),
    ],

];
