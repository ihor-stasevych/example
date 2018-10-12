<?php

namespace App\AddHash\AdminPanel\Application\Command\User\PaswordRecovery;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordRecoveryCommand implements UserPasswordRecoveryCommandInterface
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

	/**
	 * @var string
	 *
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 */
	private $password;

	/**
	 * @var string
	 *
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 * @Assert\Expression(expression="this.comparePasswords()")
	 */
	private $confirmPassword;

	public function __construct(
		$hash, $password, $confirmPassword
	)
	{
		$this->hash = $hash;
		$this->password = $password;
		$this->confirmPassword = $confirmPassword;
	}

	public function getHash()
	{
		return $this->hash;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getConfirmPassword()
	{
		return $this->confirmPassword;
	}

	/**
	 * @return bool
	 */
	public function comparePasswords()
	{
		return $this->password == $this->confirmPassword;
	}
}