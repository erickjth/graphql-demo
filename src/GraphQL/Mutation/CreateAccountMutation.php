<?php declare(strict_types = 1);

namespace Demo\GraphQL\Mutation;

use Demo\Data\Account;
use Demo\GraphQL\Type\Scalar\EmailType;
use Demo\GraphQL\Type\AccountType;
use GraphQL\Type\Definition\{ObjectType, InputObjectType, Type, ResolveInfo};

/**
* Create an account
*/
class CreateAccountMutation
{
	/**
	 * Gets an array with the mutation definition
	 *
	 * @return array  Mutation Definition
	 */
	public static function getDefinition() : array
	{
		return [
			'name' => 'createAccount',
			'type' => new ObjectType([
				'name' => 'CreateAccountPayload',
				'fields' => [
					'account' => AccountType::getInstance()
				]
			]),
			'args' => [
				'input' => new InputObjectType([
					'name' => 'CreateAccountInput',
					'fields' => [
						'firstName' => Type::nonNull(Type::string()), // Shorthand way
						'lastName' => Type::nonNull(Type::string()),
						'email' => Type::nonNull(EmailType::getInstance()),
					]
				])
			],
			'resolve' => function ($obj, $args, $context, ResolveInfo $info)
			{
				$account = [
					'id' => (string) rand(),
					'firstName' => $args['input']['firstName'],
					'lastName' => $args['input']['lastName'],
					'email' => $args['input']['email'],
					'createdDt' => gmdate('Y-m-d h:i:s'),
				];

				return [
					'account' => new Account($account)
				];
			}
		];
	}
}
