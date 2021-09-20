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

//    'links' => [
//        public_path('storage') => storage_path('app/public'),
//    ],

];
