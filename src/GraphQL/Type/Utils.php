<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type;

use GraphQL\Type\Definition\{ObjectType, Type, ResolveInfo};

/**
 * Class utils for object types
 */
class Utils
{
	const NODE_ID_SEPARATOR = ':';

	/**
	 * Get a global field Id with an opaque field (id by default)
	 *
	 * @param  ObjectType  $type    Object reference
	 * @param  string      $field   Field to opaque
	 *
	 * @return array                Field definition
	 */
	public static function globalIdField(ObjectType $type, $field = 'id')
	{
		return [
			'name' => 'id',
			'description' => 'The ID of an object',
			'type' => Type::nonNull(Type::id()),
			'resolve' => function($obj, $args, $context, ResolveInfo $info) use ($type, $field) {
				return self::toGlobalId($type->toString(), $obj[$field]);
			}
		];
	}

	/**
	 * Convert a id to an globalId
	 *
	 * @param  string $typeName   Object Type Name
	 * @param  string $id         Id to opaque
	 *
	 * @return string           Global id
	 */
	public static function toGlobalId(string $typeName, string $id)
	{
		return base64_encode($typeName . self::NODE_ID_SEPARATOR . $id);
	}

	/**
	 * Convert a global id on real id and object type
	 *
	 * @param  string $globalId  Global Id
	 *
	 * @return array           Object type and id
	 */
	public static function fromGlobalId(string $globalId)
	{
		$unbasedGlobalId = base64_decode($globalId);

		$delimiterPos = strpos($unbasedGlobalId, self::NODE_ID_SEPARATOR);

		return [
			'type' => substr($unbasedGlobalId, 0, $delimiterPos),
			'id' => substr($unbasedGlobalId, $delimiterPos + 1)
		];
	}

	/**
	 * Get the real id from a provided globalId checking its object type.
	 *
	 * @param  string     $globalId  Global Id
	 * @param  ObjectType $type      Object reference to check
	 *
	 * @return string               Real id
	 */
	public static function getIdFromGlobalId(string $globalId, ObjectType $type)
	{
		$componenent = self::fromGlobalId($globalId);

		// Check Object type
		if (isset($componenent['type']) === false || $componenent['type'] !== $type->toString())
		{
			throw new InvalidArgumentException('Invalid type for id ' . $globalId);
		}

		return $componenent['id'];
	}
}