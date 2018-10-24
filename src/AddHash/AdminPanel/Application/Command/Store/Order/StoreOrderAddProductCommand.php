<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Order;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\Validation\CustomValidator\ValidatorIntegerTrait;
use App\AddHash\AdminPanel\Domain\Store\Order\Command\StoreOrderAddProductCommandInterface;

final class StoreOrderAddProductCommand implements StoreOrderAddProductCommandInterface
{
    use ValidatorIntegerTrait;

	/**
	 * @var int
	 * @Assert\NotNull(),
     * @Assert\Expression(expression="this.isInteger(value)")
	 */
	private $product;

	/**
	 * @var int
	 * @Assert\NotNull(),
     * @Assert\Expression(expression="this.isInteger(value)")
	 */
	private $order;

	public function __construct($order, $product)
	{
		$this->order = $order;
		$this->product = $product;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function getProduct()
	{
		return $this->product;
	}
}