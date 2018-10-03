<?php

namespace App\AddHash\AdminPanel\Application\Command\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use App\AddHash\AdminPanel\Domain\User\Command\UserRegisterCommandInterface;

class UserRegisterCommand implements UserRegisterCommandInterface
{
	private $userName;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $email;

	private $backupEmail;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $password;

	private $confirmPassword;

	private $firstName;

	private $lastName;

	private $phone;

	private $roles;

    private $ip;

    private $userAgent;

	public function __construct(
		$userName,
		$email,
		$backupEmail,
		$password,
		$roles,
        $ip,
        $userAgent
	)
	{
		$this->userName = $userName;
		$this->email = $email;
		$this->backupEmail = $backupEmail;
		$this->password = $password;
		#$this->confirmPassword = $confirmPassword;
		#$this->firstName = $firstName;
		#$this->lastName = $lastName;
		#$this->phone = $phone;
		$this->roles = $roles;
		$this->ip = $ip;
		$this->userAgent = $userAgent;
	}

	public function comparePasswords(): bool
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
		return new Phone($this->phone);
	}

	public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}