<?php

declare(strict_types=1);

use TeraBlaze\HttpBase\Request;
use TeraBlaze\HttpBase\Response;

require_once __DIR__ . "/../vendor/autoload.php";

if(is_file(dirname(__DIR__) . '/.env')) {
    (new \Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(
        explode(',', $trustedProxies),
        Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST
    );
}

if ($trustedHosts = '^(localhost|terablaze.terablaze.test)$' ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);

/** @var Request $request */
$request = Request::createFromGlobals();

/** @var Response */
$response = $kernel->handle($request);

$response->send();
