<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use Demo\Data\DataSource;
use Demo\GraphQL\Type\{AccountType, CategoryType, NodeInterface, Utils};
use GraphQL\Type\Definition\{ObjectType, Type, ResolveInfo};

/**
* Post Type class
*/
class PostType extends ObjectType
{
	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ObjectType  Post Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'Post',
			'description' => 'A post object.',
			'fields' => function () {
				return [
					'id' => Utils::globalIdField(self::$intance, 'id'),
					'realId' => [
						'type' => Type::string(),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							$globalId = Utils::toGlobalId(self::$intance->toString(), $obj['id']);
							$idComponents = Utils::fromGlobalId($globalId);
							return $globalId;
						}
					],
					'title' => Type::nonNull(Type::string()),
					'body' => [
						'type' => Type::nonNull(Type::string()),
						'args' => [
							'maxLength' => Type::int()
						],
						'resolve' => [self::$intance, 'resolveBody'],
					],
					'createdDt' => Type::nonNull(Type::string()),
					'owner' => [
						'type' => Type::nonNull(AccountType::getInstance()),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return DataSource::findAccountById($obj['ownerId']);
						}
					],
					'category' => [
						'type' => Type::nonNull(CategoryType::getInstance()),
						'resolve' => function ($obj, $args, $context, ResolveInfo $info) {
							return DataSource::findCategoryById($obj['categoryId']);
						}
					],
				];
			},
			'interfaces' => [
				NodeInterface::getInstance(),
			],
		]);
	}

	public function resolveBody($obj, $args, $context, ResolveInfo $info)
	{
		$text = strip_tags($obj['body']);

		if (empty($args['maxLength']) === false)
		{
			$text = mb_substr($text, 0, $args['maxLength']) . '...';
		}

		return $text;
	}
}