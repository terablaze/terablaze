<?php

$scriptName = $_SERVER['SCRIPT_NAME'];
$virtualLocation = preg_replace('#(/public)?/[^/]*\.php(.*)$#', '/', $scriptName);

return [
    'app_name' => $_SERVER['APP_NAME'],
    'app_id' => $_SERVER['APP_ID'],
    'db_prefix' => '',
    'virtual_location' => $virtualLocation,
    'environment' => $_SERVER['APP_ENV'],
    'noreply_email' => $_SERVER['NOREPLY_EMAIL'],
];