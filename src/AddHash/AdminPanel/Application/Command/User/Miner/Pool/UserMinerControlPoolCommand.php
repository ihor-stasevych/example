<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;

class UserMinerControlPoolCommand implements UserMinerControlPoolCommandInterface
{
	/**
	 * @var int
	 * @Assert\NotBlank()
	 */
	private $minerId;

	public function __construct($minerId)
	{
		$this->minerId = $minerId;
	}

	public function getMinerId(): int
    {
        return $this->minerId;
    }
}