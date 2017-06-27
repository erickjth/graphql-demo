<?php declare(strict_types = 1);

namespace Demo\GraphQL\Mutation;

use Demo\Data\Category;
use Demo\GraphQL\Type\CategoryType;
use GraphQL\Type\Definition\{ObjectType, InputObjectType, Type, ResolveInfo};

/**
* Create a category
*/
class CreateCategoryMutation
{
	/**
	 * Gets an array with the mutation definition
	 *
	 * @return array  Mutation Definition
	 */
	public static function getDefinition() : array
	{
		return [
			'name' => 'createCategory',
			'type' => new ObjectType([
				'name' => 'CreateCategoryPayload',
				'fields' => [
					'category' => CategoryType::getInstance()
				]
			]),
			'args' => [
				'input' => new InputObjectType([
					'name' => 'CreateCategoryInput',
					'fields' => [
						'name' => Type::nonNull(Type::string()),
					]
				])
			],
			'resolve' => function ($obj, $args, $context, ResolveInfo $info)
			{
				$category = [
					'id' => (string) rand(),
					'name' => $args['input']['name'],
					'createdDt' => gmdate('Y-m-d h:i:s'),
				];

				return [
					'category' => new Category($category),
				];
			}
		];
	}
}
