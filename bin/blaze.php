#!/usr/bin/env php
<?php

use TeraBlaze\DotEnv\DotEnv;
use TeraBlaze\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

if (!in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true)) {
    echo 'Warning: The console should be invoked via the CLI version of PHP, not the ' . PHP_SAPI . ' SAPI' . PHP_EOL;
}

set_time_limit(0);

require_once __DIR__ . "/../vendor/autoload.php";

if (!class_exists(Application::class) || !class_exists(DotEnv::class)) {
    throw new LogicException('You should install all composer dependencies');
}

$input = new ArgvInput();
if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
    putenv('APP_ENV=' . $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $env);
}

if ($input->hasParameterOption('--no-debug', true)) {
    putenv('APP_DEBUG=' . $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '0');
}

//(new DotEnv(dirname(__DIR__).'/.env'))->load();

$kernel = new Kernel('dev', true);
$application = new Application($kernel);

$application->run($input);
