<?php declare(strict_types = 1);

namespace Demo\Data;

/**
 * Account model
 */
class Account extends AbstractModel
{
	public $id;
	public $firstName;
	public $lastName;
	public $email;
	public $createdDt;
}