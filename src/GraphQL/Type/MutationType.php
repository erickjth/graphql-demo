<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\DataSource;
use Demo\GraphQL\Mutation\{CreateAccountMutation, CreateCategoryMutation, CreatePostMutation};
use GraphQL\Type\Definition\{ObjectType, InputObjectType, Type, ResolveInfo};

/**
* Mutation Type class
*/
class MutationType extends ObjectType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ObjectType  Mutation Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Mutation',
			'description' => 'Mutation Root',
			'fields' => [
				CreateAccountMutation::getDefinition(),
				CreateCategoryMutation::getDefinition(),
				CreatePostMutation::getDefinition(),
			]
		]);
	}
}