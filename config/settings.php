<?php declare(strict_types = 1);

return [
	'displayErrorDetails' => true, // set to false in production
	'addContentLengthHeader' => false, // Allow the web server to send the content-length header

	// Monolog settings
	'logger' => [
		'name' => 'graphql-server',
		'path' => __DIR__ . '/../logs/app.log',
		'level' => \Monolog\Logger::DEBUG,
	],
];