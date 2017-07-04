<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\DataSource;
use Demo\GraphQL\Type\{NodeInterface, PostType};
use Demo\GraphQL\Type\Scalar\EmailType;
use GraphQL\Type\Definition\{ObjectType, Type, ResolveInfo};

/**
* Account Type class
*/
class AccountType extends ObjectType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ObjectType  Account Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Account',
			'description' => 'An account object.',
			'fields' => function () {
				return [
					'id' => Utils::globalIdField(self::$intance, 'id'),
					'firstName' => Type::nonNull(Type::string()),
					'lastName' => Type::nonNull(Type::string()),
					'email' => Type::nonNull(EmailType::getInstance()),
					'createdDt' => Type::nonNull(Type::string()),
					'posts' => [
						'type' => Type::listOf(PostType::getInstance()),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return DataSource::findPosts(null, [], ['ownerId' => $obj['id']]);
						}
					],
					'totalPosts' => [
						'type' => Type::int(),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return count(DataSource::findPosts(null, [], ['ownerId' => $obj['id']]));
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