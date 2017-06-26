<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\DataSource;
use Demo\GraphQL\Type\{NodeInterface, PostType};
use GraphQL\Type\Definition\{ObjectType, Type, ResolveInfo};

/**
* Category Type class
*/
class CategoryType extends ObjectType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ObjectType  Category Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Category',
			'description' => 'A category object.',
			'fields' => function () {
				return [
					'id' => Utils::globalIdField(self::$intance, 'id'),
					'name' => Type::nonNull(Type::string()),
					'email' => Type::nonNull(Type::string()),
					'createdDt' => Type::nonNull(Type::string()),
					'posts' => [
						'type' => Type::listOf(PostType::getInstance()),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return DataSource::findPosts(null, [], ['categoryId' => $obj['id']]);
						}
					],
					'totalPosts' => [
						'type' => Type::int(),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return count(DataSource::findPosts(null, [], ['categoryId' => $obj['id']]));
						}
					]
				];
			},
			'interfaces' => [
				NodeInterface::getInstance(),
			],
		]);
	}
}