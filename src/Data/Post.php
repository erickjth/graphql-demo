<?php declare(strict_types = 1);

namespace Demo\Data;

/**
 * Post model
 */
class Post extends AbstractModel
{
	public $id;
	public $title;
	public $body;
	public $ownerId;
	public $categoryId;
	public $createdDt;
}