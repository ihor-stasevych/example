<?php

namespace App\AddHash\AdminPanel\Domain\Store;

use Doctrine\Common\Collections\ArrayCollection;

class Cart
{
	/** @var ArrayCollection */
	private $product;

	/** @var integer */
	private $createdAt;

	/** @var integer */
	private $quantity;
}