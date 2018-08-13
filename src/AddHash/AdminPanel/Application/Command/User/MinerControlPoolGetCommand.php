<?php

namespace App\AddHash\AdminPanel\Application\Command\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\MinerControlPoolGetCommandInterface;

class MinerControlPoolGetCommand implements MinerControlPoolGetCommandInterface
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