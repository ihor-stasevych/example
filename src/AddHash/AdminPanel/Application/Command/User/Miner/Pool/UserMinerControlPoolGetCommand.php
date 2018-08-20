<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;

class UserMinerControlPoolGetCommand implements UserMinerControlPoolGetCommandInterface
{
	/**
	 * @var int
	 * @Assert\NotBlank()
	 */
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function getId(): int
    {
        return $this->id;
    }
}