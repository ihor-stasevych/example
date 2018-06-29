<?php

namespace App\AddHash\AdminPanel\Domain\User;

use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

class User
{
	/** @var UserId */
	private $id;

	/** @var string */
	private $userName;

	/** @var Email */
	private $email;

	/** @var Email */
	private $backupEmail;

	/** @var string */
	private $firstName;

	/** @var string */
	private $lastName;

	/** @var Phone */
	private $phone;


	public function __construct(
		UserId $id,
		string $userName,
		Email $email,
		Email $backupEmail,
		string $firstName,
		string $lastName,
		Phone $phoneNumber)
	{
		$this->setId($id);
		$this->setUserName($userName);
		$this->setEmail($email);
		$this->setBackupEmail($backupEmail);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setPhoneNumber($phoneNumber);
	}

	private function setId(UserId $id)
	{
		$this->id = $id;
	}

	private function setUserName(string $userName)
	{
		$this->userName = $userName;
	}

	private function setEmail(Email $email)
	{
		$this->email = $email;
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
}