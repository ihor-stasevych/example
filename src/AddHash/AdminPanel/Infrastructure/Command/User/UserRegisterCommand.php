<?php

namespace App\AddHash\AdminPanel\Infrastructure\Command\User;


use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterCommand implements UserRegisterCommandInterface
{

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $userName;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $email;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $backupEmail;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $password;

	/**
	 * @var string
	 *
	 * @Assert\NotBlank()
	 * @Assert\Expression(expression="this.comparePasswords()")
	 */
	private $confirmPassword;


	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $firstName;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $lastName;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $phone;

	/**
	 * @var array
	 */
	private $roles;


	/**
	 * UserRegisterCommand constructor.
	 *
	 * @param $userName
	 * @param $email
	 * @param $backupEmail
	 * @param $password
	 * @param $confirmPassword
	 * @param $firstName
	 * @param $lastName
	 * @param $phone
	 * @param $roles
	 */
	public function __construct(
		$userName = '',
		$email = '',
		$backupEmail = '',
		$password = '',
		$confirmPassword = '',
		$firstName = '',
		$lastName = '',
		$phone = 0,
		$roles = []
	)
	{
		$this->userName = $userName;
		$this->email = $email;
		$this->backupEmail = $backupEmail;
		$this->password = $password;
		$this->confirmPassword = $confirmPassword;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->phone = $phone;
		$this->roles = $roles;
	}

	/**
	 * @return bool
	 */
	public function comparePasswords()
	{
		return $this->password == $this->confirmPassword;
	}

	public function getEmail(): Email
	{
		return new Email($this->email);
	}

	public function getUserName(): string
	{
		return $this->userName;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getBackupEmail(): Email
	{
		return new Email($this->backupEmail);
	}

	public function getRoles(): array
	{
		return $this->roles;
	}

	public function getPhone(): Phone
	{
		return  new Phone($this->phone);
	}
}