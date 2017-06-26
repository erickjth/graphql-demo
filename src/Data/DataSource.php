<?php declare(strict_types = 1);

namespace Demo\Data;

use Demo\Data\{Account, Category, Post};
use InvalidArgumentException;

/**
* Static data provider
*/
class DataSource
{
	private static $accounts = [];
	private static $categories = [];
	private static $posts = [];

	/**
	 * Initiate data source
	 */
	public static function init()
	{
		$mockData = require 'mock.php';

		self::$accounts = self::getMappedDataById($mockData['accounts'], Account::class);
		self::$categories = self::getMappedDataById($mockData['categories'], Category::class);
		self::$posts = self::getMappedDataById($mockData['posts'], Post::class);
	}

	/**
	 * Get a post using the provided Id
	 *
	 * @param  string $postId  Post Id
	 *
	 * @return Post         Post object
	 */
	public static function findPostById($postId)
	{
		return self::$posts[$postId] ?? null;
	}

	/**
	 * Get an account using the provided Id
	 *
	 * @param  string $accountId  Account Id
	 *
	 * @return Account         Account object
	 */
	public static function findAccountById($accountId)
	{
		return self::$accounts[$accountId] ?? null;
	}

	/**
	 * Get a category using the provided Id
	 *
	 * @param  string $categoryId  Category Id
	 *
	 * @return Category         Category object
	 */
	public static function findCategoryById($categoryId)
	{
		return self::$categories[$categoryId] ?? null;
	}

	/**
	 * Get a limited/sorted/filtered post list.
	 *
	 * @param  int|null $limit           Number of entries
	 * @param  array    $sortOrderFields Sorting map array [$fieldName => 'ASC|DESC']
	 * @param  array    $filter          Filter map array [$fieldName => <value>]
	 *
	 * @return array                    Array of posts
	 */
	public static function findPosts(int $limit = null, array $sortOrderFields = [], array $filter = [])
	{
		$limit = $limit ?: count(self::$posts);
		return self::find(self::$posts, $limit, $sortOrderFields, $filter);
	}

	/**
	 * Get a limited/sorted/filtered account list.
	 *
	 * @param  int|null $limit           Number of entries
	 * @param  array    $sortOrderFields Sorting map array [$fieldName => 'ASC|DESC']
	 * @param  array    $filter          Filter map array [$fieldName => <value>]
	 *
	 * @return array                    Array of accounts
	 */
	public static function findAccounts(int $limit = null, array $sortOrderFields = [], array $filter = [])
	{
		$limit = $limit ?: count(self::$accounts);
		return self::find(self::$accounts, $limit, $sortOrderFields, $filter);
	}

	/**
	 * Get a limited/sorted/filtered category list.
	 *
	 * @param  int|null $limit           Number of entries
	 * @param  array    $sortOrderFields Sorting map array [$fieldName => 'ASC|DESC']
	 * @param  array    $filter          Filter map array [$fieldName => <value>]
	 *
	 * @return array                    Array of categories
	 */
	public static function findCategories(int $limit = null, array $sortOrderFields = [], array $filter = [])
	{
		$limit = $limit ?: count(self::$categories);
		return self::find(self::$categories, $limit, $sortOrderFields, $filter);
	}

	/**
	 * Helper method to map the mock data to convert in a collection of objects.
	 *
	 * @param  array  $data    Array of data
	 * @param  string $class   Collection type
	 *
	 * @return array          Collection of data with objects
	 */
	private static function getMappedDataById(array $data = [], $class)
	{
		return array_reduce($data, function($carry, $row) use ($class) {
			$carry[$row['id']] = $class::fromArray($row);
			return $carry;
		}, []);
	}

	/**
	 * Helper class to find entries in an existing data array.
	 *
	 * @param  array  $data             Array of data
	 * @param  int    $limit            Limit of entries
	 * @param  array  $sortOrderFields  Sorting map array [$fieldName => 'ASC|DESC']
	 * @param  array  $filter           Filter map array [$fieldName => <value>]
	 *
	 * @return array                  Filtered array of objects
	 */
	private static function find(array $data, int $limit, array $sortOrderFields, array $filter)
	{
		$data = array_slice($data, 0, $limit);

		// Apply filters
		if ($filter !== [])
		{
			foreach ($filter as $field => $value)
			{
				if (is_string($field) === false)
				{
					throw new InvalidArgumentException('Some filter is invalid. It should be [$field => $value]');
				}

				$data = array_filter($data, function($row) use ($field, $value) {
					return $row[$field] === $value;
				});
			}
		}

		// Apply sort order
		if ($sortOrderFields !== [])
		{
			foreach ($sortOrderFields as $field => $fieldOrder)
			{
				if (is_string($field) === false)
				{
					throw new InvalidArgumentException('Some field is invalid. It should be [$field => $order]');
				}

				$data = self::getOrderedList($data, $field, $fieldOrder === 'ASC' ? 1 : -1);
			}
		}

		return $data;
	}

	/**
	 * Helper method to apply sorting for data.
	 *
	 * @param  array       $list      Array of data.
	 * @param  string      $fieldName Field reference.
	 * @param  int|integer $sortOrder Field order.
	 *
	 * @return array                  Sorted data
	 */
	private static function getOrderedList(array $list = [], string $fieldName, int $sortOrder = 1)
	{
		if (empty($list) === true)
		{
			return [];
		}

		$firstElement = current($list);

		if (isset($firstElement[$fieldName]) === false)
		{
			throw new InvalidArgumentException('Invalid sorting field ' . $fieldName . '.');
		}

		reset($list);

		usort($list, function ($a, $b) use ($fieldName, $sortOrder) {
			return strcmp($a[$fieldName], $b[$fieldName]) * $sortOrder;
		});

		return $list;
	}
}
