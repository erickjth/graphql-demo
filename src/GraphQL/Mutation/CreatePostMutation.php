<?php declare(strict_types = 1);

namespace Demo\GraphQL\Mutation;

use Demo\Data\{DataSource, Post};
use Demo\GraphQL\Type\{AccountType, CategoryType, PostType, Utils};
use GraphQL\Type\Definition\{ObjectType, InputObjectType, Type, ResolveInfo};

/**
* Create a post
*/
class CreatePostMutation
{
	/**
	 * Gets an array with the mutation definition
	 *
	 * @return array  Mutation Definition
	 */
	public static function getDefinition() : array
	{
		return [
			'name' => 'createPost',
			'type' => new ObjectType([
				'name' => 'CreatePostPayload',
				'fields' => [
					'post' => PostType::getInstance()
				]
			]),
			'args' => [
				'input' => new InputObjectType([
					'name' => 'CreatePostInput',
					'fields' => [
						'title' => Type::nonNull(Type::string()),
						'body' => Type::nonNull(Type::string()),
						'owner' => Type::nonNull(Type::id()),
						'category' => Type::nonNull(Type::id()),
					]
				])
			],
			'resolve' => function ($obj, $args, $context, ResolveInfo $info)
			{
				$ownerId = Utils::getIdFromGlobalId($args['input']['owner'], AccountType::getInstance());
				$categoryId = Utils::getIdFromGlobalId($args['input']['category'], CategoryType::getInstance());

				$post = [
					'id' => (string) rand(),
					'title' => $args['input']['title'],
					'body' => $args['input']['body'],
					'ownerId' => $ownerId,
					'categoryId' => $categoryId,
					'createdDt' => gmdate('Y-m-d h:i:s'),
				];

				return [
					'post' => new Post($post)
				];
			}
		];
	}
}
