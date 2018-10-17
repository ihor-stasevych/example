<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;

use App\AddHash\AdminPanel\Domain\User\User;

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

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setProduct(StoreProduct $product)
    {
        $this->product = $product;
    }
}