<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\DataSource;
use Demo\GraphQL\Type\{AccountType, CategoryType, PostType, Utils};
use Demo\GraphQL\Type\Enum\SortDirectionEnum;
use GraphQL\Type\Definition\{ObjectType, InputObjectType, Type, ResolveInfo};

/**
* Query Type class
*/
class QueryType extends ObjectType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ObjectType  Query Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Query',
			'description' => 'Query Root',
			'fields' => function() {
				return [
					'posts' => [
						'type' => Type::nonNull(Type::listOf(PostType::getInstance())),
						'description' => 'Get published posts.',
						'args' => [
							'first' => Type::int(),
							'orderBy' => new InputObjectType([
								'name' => 'PostsFiltersInput',
								'description' => 'Get ordered posts by provided field.',
								'fields' => [
									'field' => Type::nonNull(Type::string()),
									'direction' => [
										'type' => SortDirectionEnum::getInstance(),
										'description' => 'Sort direction',
										'defaultValue' => SortDirectionEnum::DESC,
									],
								]
							]),
						],
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							$sortOrder = isset($args['orderBy']) ? [
								$args['orderBy']['field'] => $args['orderBy']['direction']
							] : [];

							return DataSource::findPosts($args['first'] ?? null, $sortOrder);
						}
					],

					'accounts' => [
						'type' => Type::nonNull(Type::listOf(AccountType::getInstance())),
						'description' => 'Get accounts.',
						'args' => [
							'first' => Type::int(),
							'orderBy' => new InputObjectType([
								'name' => 'AccountsFiltersInput',
								'description' => 'Get ordered accounts by provided field.',
								'fields' => [
									'field' => Type::string(),
									'direction' => [
										'type' => SortDirectionEnum::getInstance(),
										'defaultValue' => SortDirectionEnum::DESC,
									],
								]
							]),
						],
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							$sortOrder = isset($args['orderBy']) ? [
								$args['orderBy']['field'] => $args['orderBy']['direction']
							] : [];

							return DataSource::findAccounts($args['first'] ?? null, $sortOrder);
						}
					],

					'categories' => [
						'type' => Type::nonNull(Type::listOf(CategoryType::getInstance())),
						'description' => 'Get categories.',
						'args' => [
							'first' => Type::int(),
							'orderBy' => new InputObjectType([
								'name' => 'CategoriesFiltersInput',
								'description' => 'Get ordered categories by provided field.',
								'fields' => [
									'field' => Type::string(),
									'direction' => [
										'type' => SortDirectionEnum::getInstance(),
										'defaultValue' => SortDirectionEnum::DESC,
									],
								]
							]),
						],
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							$sortOrder = isset($args['orderBy']) ? [
								$args['orderBy']['field'] => $args['orderBy']['direction']
							] : [];

							return DataSource::findCategories($args['first'] ?? null, $sortOrder);
						}
					],

					'node' => [
						'name' => 'node',
						'description' => 'Fetches an object given its ID',
						'type' => NodeInterface::getInstance(),
						'args' => [
							'id' => [
								'type' => Type::nonNull(Type::id()),
								'description' => 'The ID of an object'
							]
						],
						'resolve' => [self::$intance, 'resolveNodeField']
					]
				];
			},
		]);
	}

	public function resolveNodeField($obj, $args, $context, ResolveInfo $info)
	{
		$globalId = $args['id'];

		$idComponents = Utils::fromGlobalId($globalId);

		if (isset($idComponents['type']) === false || isset($idComponents['id']) === false)
		{
			throw new InvalidArgumentException('Invalid id ' . $globalId);
		}

		$finderMethod = 'find'. $idComponents['type'] . 'byId';

		if (method_exists(DataSource::class, $finderMethod) === false)
		{
			return null;
		}

		return DataSource::$finderMethod($idComponents['id']);
	}
}