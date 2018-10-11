<?php

namespace App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryRequestCommandInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;

class UserPasswordRecoveryRequestCommand implements UserPasswordRecoveryRequestCommandInterface
{
	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $email;

	public function __construct($email)
	{
		$this->email = new Email($email);
	}

	public function getEmail(): Email
	{
		return $this->email;
	}
}