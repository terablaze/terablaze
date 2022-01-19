<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
//        'sqlite' => [
//            'driver' => 'sqlite',
//            'path' => baseDir(env('DB_PATH') . "" . env('DB_DATABASE', 'db.sqlite')),
//            'prefix' => '',
//            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
//        ],
//        "mysql" => [
//            "type" => 'mysqli',
//            "host" => 'localhost',
//            "username" => '',
//            "password" => '',
//            "schema" => '',
//            "port" => '3306',
//            "dateTimeMode" => "DATETIME",
//            "collect" => true,
//            "alias" => "database",
//        ],
    ],

    'migrations' => 'migrations',

];
