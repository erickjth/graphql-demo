<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type\Enum;

use GraphQL\Type\Definition\{EnumType, Type};

/**
* Sort direction Enum Type
*/
class SortDirectionEnum extends EnumType
{
	const ASC = 'ASC';
	const DESC = 'DESC';

	/**
	 * @var \GraphQL\Type\Definition\ObjectType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\EnumType  Enum Type instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance !== null)
		{
			return self::$intance;
		}

		return self::$intance = new static([
			'name' => 'SortDirectionEnum',
			'values' => [self::ASC, self::DESC]
		]);
	}
}