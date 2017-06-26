<?php declare(strict_types = 1);

namespace Demo\GraphQL\Type\Scalar;

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\{ScalarType, Type};
use GraphQL\Utils;

/**
 * Email type class
 */
class EmailType extends ScalarType
{
	/**
	 * @var \GraphQL\Type\Definition\ScalarType Object instance
	 */
	private static $intance = null;

	/**
	 * Gets instance of this class
	 *
	 * @return \GraphQL\Type\Definition\ScalarType  Email instance
	 */
	public static function getInstance() : Type
	{
		if (self::$intance === null)
		{
			self::$intance = new static;
		}

		return self::$intance;
	}

	/**
	 * @var string  Required: Name of the custom type
	 */
	public $name = 'Email';

	/**
	 * Serializes an internal value to include in a response.
	 *
	 * @param string $value
	 * @return string
	 */
	public function serialize($value)
	{
		return $this->parseValue($value);
	}

	/**
	 * Parses an externally provided value (query variable) to use as an input
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function parseValue($value)
	{
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new \UnexpectedValueException('Cannot represent value as email: ' . Utils::printSafe($value));
		}

		return $value;
	}

	/**
	 * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
	 *
	 * E.g.
	 * {
	 *   user(email: "user@example.com")
	 * }
	 *
	 * @param \GraphQL\Language\AST\Node $valueNode
	 * @return string
	 * @throws Error
	 */
	public function parseLiteral($valueNode)
	{
		// Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
		// error location in query:
		if (!$valueNode instanceof StringValueNode)
		{
			throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
		}

		if (filter_var($valueNode->value, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new Error('Not a valid email', [$valueNode]);
		}

		return $valueNode->value;
	}
}