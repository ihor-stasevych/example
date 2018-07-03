<?php

namespace App\AddHash\Authentication\Domain\Model;

use App\AddHash\System\GlobalContext\Identity\UserId;
use App\AddHash\System\GlobalContext\ValueObject\Email;

class User
{
	/**
	 * @var UserId
	 */
	private $id;

	private $email;

	private $password;

	private $name;

	public function __construct(UserId $id, Email $email, $password, $name)
	{
		$this->id = $id;
		$this->email = $email;
		$this->password = $password;
		$this->name = $name;
	}

	public function getId(): UserId
	{
		return $this->id;
	}

	public function getEmail() : Email
	{
		return $this->email;
	}

	public function getPassword()
	{
		return $this->password;
	}


	public function getUsername()
	{
		return $this->name;
	}
}