<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
//        "mysqlConnector" => [
//            "type" => 'mysqli_legacy',
//            "host" => 'localhost',
//            "username" => '',
//            "password" => '',
//            "schema" => '',
//            "port" => '3306',
//            "dateTimeMode" => "DATETIME",
//            "collect" => true,
//            "alias" => "legacy_database",
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
