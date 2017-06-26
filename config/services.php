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
	},
	// Slim services
	'errorHandler' => function($container)
	{
		return function ($request, $response, $exception) use ($container) {
			$container['logger']->error('{error}', [ 'error' => $exception->getMessage() ]);
			return $container['response']->withJson(['error' => $exception->getMessage()])->withStatus(400);
		};
	},

	'notFoundHandler' => function($container)
	{
		return function ($request, $response, $exception) use ($container) {
			return $container['response']->withJson(['error' => 'Page not found'])->withStatus(404);
		};
	},

	'notAllowedHandler' => function($container)
	{
		return function ($request, $response, $methods) use ($container) {
			return $container['response']->withJson(['error' => 'Method must be one of: ' . implode(', ', $methods)])->withStatus(405);
		};
	},

	'phpErrorHandler' => function($container)
	{
		return function ($request, $response, $exception) use ($container) {
			$container['logger']->error('PHP ERROR: {file} {line} {error}', [
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
				'error' => $exception->getMessage(),
			]);

			return $container['response']->withJson([
				'error' => 'Something went wrong.'
			])->withStatus(500);
		};
	},
];

foreach ($services as $name => $callable)
{
	$container[$name] = $callable;
}

return $container;