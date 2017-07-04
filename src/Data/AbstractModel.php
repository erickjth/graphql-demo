<?php declare(strict_types = 1);

namespace Demo\Data;

use ArrayAccess;
use GraphQL\Utils;

/**
 * Global class for models
 */
abstract class AbstractModel implements ArrayAccess
{
	/**
	 * Model constructor
	 *
	 * @param array $data Data attributes
	 */
	public function __construct(array $data)
	{
		Utils::assign($this, $data);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set(string $name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get(string $name)
	{
		return $this->get($name);
	}

	/**
	 * Get an element with array like access.
	 *
	 * @param string   $key   Key.
	 *
	 * @return mixed Session value.
	 */
	public function offsetGet($key)
	{
		return $this->get($key);
	}

	/**
	 * Set an element with array like access.
	 *
	 * @param string   $key   Key.
	 * @param string   $val   Value.
	 */
	public function offsetSet($key, $val)
	{
		$this->set($key, $val);
	}

	/**
	 * Check if a key exists.
	 *
	 * @param string   $key   Key.
	 *
	 * @return bool True if key exists.
	 */
	public function offsetExists($key)
	{
		return $this->has($key);
	}

	/**
	 *  Method not allowed
	 * @param string $key Key.
	 */
	public function offsetUnset($key)
	{
		throw new \Exception('Unset method is not allowed for models.');
	}

	/**
	 * Set a data attribute.
	 *
	 * @param string  $key   Key.
	 * @param mixed   $val   Value.
	 */
	public function set(string $key, $val)
	{
		if ($this->has($key))
		{
			$this->$key = $val;
		}
	}

	/**
	 * Get a data element.
	 *
	 * @param string   $key   Key.
	 *
	 * @return mixed value.
	 */
	public function get(string $key)
	{
		return $this->has($key) ? $this->$key : null;
	}

	/**
	 * Check if a key exists.
	 *
	 * @param string   $key   Key.
	 *
	 * @return bool True if key exists.
	 */
	public function has(string $key) : bool
	{
		return property_exists($this, $key);
	}

	/**
	 * Fill the object from an array of data
	 *
	 * @param  array  $fields Fields
	 *
	 * @return self         Object instance
	 */
	public static function fromArray(array $fields) : self
	{
		return new static($fields);
	}

	/**
	 * Returns a data as array
	 *
	 * @return array Array of attributes
	 */
	public function toArray() : array
	{
		return get_object_vars($this);
	}
}