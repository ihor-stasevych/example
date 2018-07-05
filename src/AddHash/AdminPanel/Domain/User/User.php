<?php

namespace App\AddHash\AdminPanel\Domain\User;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use App\AddHash\System\Lib\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
	/** @var integer */
	private $id = null;

	/** @var string */
	private $userName;

	/** @var Email */
	private $email;

	/** @var string */
	private $password;

	/** @var Email */
	private $backupEmail;

	/** @var string */
	private $firstName;

	/** @var string */
	private $lastName;

	/** @var Phone */
	private $phone;

	/** @var string */
	private $token;

	/** @var \DateTime */
	private $createdAt;

	/** @var \DateTime */
	private $updatedAt;


	public function __construct(
		int $id = null,
		string $userName,
		Email $email,
		string $password,
		Email $backupEmail,
		string $firstName,
		string $lastName,
		Phone $phoneNumber)
	{
		$this->setId($id);
		$this->setUserName($userName);
		$this->setEmail($email);
		$this->setPassword($password);
		$this->setBackupEmail($backupEmail);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setPhoneNumber($phoneNumber);
		$this->token = Uuid::generate();
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getPassword()
	{
		return $this->password;
	}

	private function setId($id = null)
	{
		if ($id != null) {
			$this->id = $id;
		}
	}

	public function getEmail()
	{
		return $this->email;
	}

	private function setUserName(string $userName)
	{
		$this->userName = $userName;
	}

	private function setEmail(Email $email)
	{
		$this->email = $email;
	}

	private function setPassword(string $password)
	{
		$this->password = $password;
	}

	private function setBackupEmail(Email $email)
	{
		$this->backupEmail = $email;
	}

	private function setFirstName(string $firstName)
	{
		$this->firstName = $firstName;
	}

	private function setLastName(string $lastName)
	{
		$this->lastName = $lastName;
	}

	private function setPhoneNumber($phoneNumber)
	{
		$this->phone = $phoneNumber;
	}

	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	public function getSalt()
	{
		return '';
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername()
	{
		return $this->userName;
	}

	/**
	 * Removes sensitive data from the user.
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}
}