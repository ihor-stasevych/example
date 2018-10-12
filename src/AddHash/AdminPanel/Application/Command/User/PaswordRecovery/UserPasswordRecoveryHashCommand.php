<?php

namespace App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryHashCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordRecoveryHashCommand implements UserPasswordRecoveryHashCommandInterface
{
	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *      min = 10,
	 *      minMessage = "Your hash must be at least {{ limit }} characters long"
	 * )
	 */
	private $hash;

	public function __construct($hash)
	{
		$this->hash = $hash;
	}

	public function getHash()
	{
		return $this->hash;
	}
}