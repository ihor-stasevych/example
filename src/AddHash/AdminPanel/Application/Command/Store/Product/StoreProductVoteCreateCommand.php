<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Product;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductVoteCreateCommandInterface;

class StoreProductVoteCreateCommand implements StoreProductVoteCreateCommandInterface
{
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="digit")
     */
	private $productId;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="digit")
     */
	private $value;

	public function __construct($productId, $value)
	{
		$this->productId = $productId;
		$this->value = $value;
	}

	public function getProductId(): int
	{
		return $this->productId;
	}

	public function getValue(): int
	{
		return $this->value;
	}
}