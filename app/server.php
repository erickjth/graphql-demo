<?php declare(strict_types = 1);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use Demo\Middleware\GraphQLServerMiddleware;

$services = require 'config/services.php';

$application = new \Slim\App($services);

$application->post('/', GraphQLServerMiddleware::class);

$application->run();
