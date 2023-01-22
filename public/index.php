<?php

declare(strict_types=1);

use Terablaze\HttpBase\Request;
use Terablaze\HttpBase\Response;

require_once __DIR__ . "/../vendor/autoload.php";

(new \Symfony\Component\Dotenv\Dotenv())->usePutenv()->bootEnv(dirname(__DIR__) . '/.env');

//if ($trustedHosts = '^(localhost|terablaze.terablaze.test)$' ?? false) {
//    Request::setTrustedHosts([$trustedHosts]);
//}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);

/** @var Request $request */
$request = Request::createFromGlobals();

/** @var Response */
$response = $kernel->handle($request);

$response->send();
