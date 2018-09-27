<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StoreOrderCreateCommand implements StoreOrderCreateCommandInterface
{
	/**
	 * @var array
	 * @Assert\Type(type="array")
	 * @Assert\NotNull()
	 * @Assert\Count(min=1)
	 */
	private $products;

	public function __construct($products = [])
	{
		$this->products = $products;
	}

	public function getProducts()
	{
		return $this->products;
	}

	public function getItemsPriceTotal()
	{
		// TODO: Implement getItemsPriceTotal() method.
	}
}