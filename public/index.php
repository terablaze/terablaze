<?php

declare(strict_types=1);

use App\AppKernel;
use Symfony\Component\Dotenv\Dotenv;
use TeraBlaze\HttpBase\Request;
// TeraBlaze Version
define("TERABLAZE_VERSION", 'dev-master');

require_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
} catch (Exception $e) {

}

$debug = isset($_SERVER['APP_DEBUG']) ? (bool) $_SERVER['APP_DEBUG'] : true;
$kernel = new AppKernel($_SERVER['APP_ENV'], $debug);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();

