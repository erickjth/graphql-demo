<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\{Account, Category, Post};
use Demo\GraphQL\Type\{AccountType, CategoryType, PostType};
use GraphQL\Type\Definition\{Type, InterfaceType};

/**
* Node Interface class
*/
class NodeInterface extends InterfaceType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\InterfaceType Node interface instance
	 */
	public static function getInstance() : InterfaceType
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Node',
			'description' => 'An object with an ID',
			'fields' => function () {
				return [
					'id' => [
						'type' => Type::nonNull(Type::id()),
						'description' => 'The id of the object',
					]
				];
			},
			'resolveType' => function($object, $context, $info) {

				if ($object instanceOf Post)
				{
					return PostType::getInstance();
				}
				else if ($object instanceOf Account)
				{
					return AccountType::getInstance();
				}
				else if ($object instanceOf Category)
				{
					return CategoryType::getInstance();
				}

				return null;
			},
		]);
	}
}

