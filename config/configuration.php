<?php

use TeraBlaze\Container\Container;
use TeraBlaze\Core\Kernel\Kernel;

$container = Container::getContainer();
/** @var Kernel */
$kernel = $container->get('app.kernel');
include_once $kernel->getProjectDir() . "/config/constants.php";

return [
];

