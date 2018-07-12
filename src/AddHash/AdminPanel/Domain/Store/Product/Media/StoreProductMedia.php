<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Media;

class StoreProductMedia
{
	const TYPE_PICTURE = 1;
	const TYPE_VIDEO = 2;

	private $id;

	private $product_id;

	private $type;

	private $src;

	private $product;

	protected $typeAlias = [
		self::TYPE_PICTURE => 'picture',
		self::TYPE_VIDEO => 'video'
	];

	public function getSrc()
	{
		return $this->src;
	}

	public function getType()
	{
		return $this->typeAlias[$this->type] ?? 'undefined';
	}
}