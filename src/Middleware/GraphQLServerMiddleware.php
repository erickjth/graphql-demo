<?php declare(strict_types = 1);

namespace Demo\Middleware;

use GraphQL\{GraphQL, Schema};
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
* GraphQl Middleware class
*/
class GraphQLServerMiddleware
{
	/**
	 * @var ContainerInterface  Service container
	 */
	public $container;

	/**
	 * Class constructor
	 *
	 * @param ContainerInterface $container Service container
	 */
	function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Invokable method.
	 *
	 * @param  ServerRequestInterface $request   Request object.
	 * @param  ResponseInterface      $response  Response object.
	 * @param  array                  $args      Route arguments
	 *
	 * @return ResponseInterface                 Response
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
	{
		// $schema = new Schema([
		// 	'query' => null, // Query Type
		// 	'mutation' => null, // MutationType
		// ]);

		$this->container['logger']->info('New request!!');

		return $response->withJson([
			'Hello' => 'Jobsity'
		]);
	}
}
