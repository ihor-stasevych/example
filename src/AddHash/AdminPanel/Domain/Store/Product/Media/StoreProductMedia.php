<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product\Media;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class StoreProductMedia
{
	const TYPE_PICTURE = 1;

	const TYPE_VIDEO = 2;


	private $id;

	private $type;

	private $src;

	private $product;

	protected $typeAlias = [
		self::TYPE_PICTURE => 'picture',
		self::TYPE_VIDEO   => 'video'
	];

	public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSrc()
	{
		return $this->src;
	}

	public function getType()
	{
		return $this->typeAlias[$this->type] ?? 'undefined';
	}

	public function setSrc($src)
    {
        $this->src = $src;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setProduct(StoreProduct $product)
    {
        $this->product = $product;
    }
}