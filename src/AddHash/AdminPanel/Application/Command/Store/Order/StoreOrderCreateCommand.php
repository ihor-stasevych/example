<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderCreateCommandInterface;

/**
 * @GroupSequence({"Type", "StoreOrderCreateCommand"})
 */
final class StoreOrderCreateCommand implements StoreOrderCreateCommandInterface
{
	/**
	 * @var array
     * @Assert\NotBlank(groups={"Type"})
     * @Assert\Type("array", groups={"Type"})
	 * @Assert\Count(min=1)
	 */
	private $products;

	public function __construct($products = [])
	{
		$this->products = array_filter($products);
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