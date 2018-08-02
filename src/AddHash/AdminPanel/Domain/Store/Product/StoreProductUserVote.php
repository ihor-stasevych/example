<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use Doctrine\Common\Collections\ArrayCollection;

class StoreProductUserVote
{
	private $id;

	private $user;

	private $product;

	private $value;

	public function __construct($value, $id = null)
	{
        $this->id = $id;
		$this->value = $value;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getUser()
    {
        return $this->user;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }
}