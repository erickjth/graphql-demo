<?php declare(strict_types = 1);

namespace Demo\Middleware;

use Demo\Data\DataSource;
use Demo\GraphQL\Type\{QueryType, MutationType};
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
		$requestBody = $request->getParsedBody();

		// Get information from post body
		$query = $requestBody['query'] ?? null;
		$variableValues = $requestBody['variables'] ?? null;
		$operationName = $requestBody['operationName'] ?? null;

		if (is_array($variableValues) === false)
		{
			$variableValues = json_decode((string)$variableValues, true);
		}

		// Log request
		$this->container['logger']->info("Query: {query}\nVariables: {variables}.\nOperation: {operationName}", [
			'query' => preg_replace('#(\r|\n|\t)+#i', '', $query),
			'variables' => $variableValues,
			'operationName' => $operationName,
		]);

		// Get defined schema
		$schema = new Schema([
			'query' => QueryType::getInstance(), // Query Type
			'mutation' => MutationType::getInstance(), // MutationType
		]);

		// Initialize data source
		DataSource::init();

		// Execute the sent query against the existing schema
		$result = GraphQL::execute($schema, $query, null, $this, $variableValues, $operationName);

		$errors = $result['errors'] ?? null;

		if ($errors !== null && $this->container['settings']['dev'] === false)
		{
			throw new \Exception($errors[0]['message'] ?? 'Unknown error.');
		}

		return $response->withJson($result)->withStatus($errors ? 400 : 200);
	}
}
