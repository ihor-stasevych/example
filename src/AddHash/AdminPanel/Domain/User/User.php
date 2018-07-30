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

	/** @var array */
	private $roles;

	/** @var string */
	private $token;

	/** @var \DateTime */
	private $createdAt;

	/** @var \DateTime */
	private $updatedAt;

	private $order;


	public function __construct(
		int $id = null,
		string $userName,
		Email $email,
		string $password,
		Email $backupEmail,
		string $firstName,
		string $lastName,
		Phone $phoneNumber,
		array $roles
	)
	{
		$this->setId($id);
		$this->setUserName($userName);
		$this->setEmail($email);
		$this->setPassword($password);
		$this->setBackupEmail($backupEmail);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setPhoneNumber($phoneNumber);
		$this->setRoles($roles);
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

    public function getBackupEmail()
    {
        return $this->backupEmail;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPhoneNumber()
    {
        return $this->phone;
    }

	private function setUserName(string $userName)
	{
		$this->userName = $userName;
	}

	public function setEmail(Email $email)
	{
		$this->email = $email;
	}

	public function setPassword(string $password)
	{
		$this->password = $password;
	}

	public function setBackupEmail(Email $email)
	{
		$this->backupEmail = $email;
	}

	public function setFirstName(string $firstName)
	{
		$this->firstName = $firstName;
	}

	public function setLastName(string $lastName)
	{
		$this->lastName = $lastName;
	}

	public function setPhoneNumber($phoneNumber)
	{
		$this->phone = $phoneNumber;
	}

	private function setRoles(array $roles)
	{
		$this->roles = array_unique($roles);
	}

	public function getRoles()
	{
		//return ['ROLE_ADMIN'];
		return $this->roles;
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
		//$this->password = null;
	}
}