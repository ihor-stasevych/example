<?php

namespace App\AddHash\AdminPanel\Application\Command\Store\Product;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductGetCommandInterface;

class StoreProductGetCommand implements StoreProductGetCommandInterface
{
    /**
     * @var int
     * @Assert\Type("numeric")
     */
	private $id;

	public function __construct($id = null)
	{
		$this->id = $id;
	}

	public function getId(): ?int
    {
        return $this->id;
    }
}