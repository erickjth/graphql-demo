<?php declare(strict_types = 1);

use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Logger;
use Slim\Container;

$settings = require __DIR__ . '/settings.php';

$container = new Container(['settings' => $settings]);

$services = [
	'logger' => function ($container)
	{
		$settings = $container->get('settings')['logger'];

		$logger = new Logger($settings['name'], [
			new StreamHandler($settings['path'], Logger::DEBUG),
			new StreamHandler($settings['path'], Logger::WARNING),
		], [new PsrLogMessageProcessor]);

		return $logger;
	}
];

foreach ($services as $name => $callable)
{
	$container[$name] = $callable;
}

return $container;