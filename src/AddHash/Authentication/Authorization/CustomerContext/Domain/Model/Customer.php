<?php

namespace App\AddHash\Authentication\Authorization\CustomerContext\Domain\Model;

use App\AddHash\System\GlobalContext\Identity\CustomerId;
use App\AddHash\System\GlobalContext\ValueObject\Email;

class Customer
{
	/**
	 * @var CustomerId
	 */
	private $id;

	private $email;

	private $password;

	private $name;

	public function __construct(CustomerId $id, Email $email, $password, $name)
	{
		$this->id = $id;
		$this->email = $email;
		$this->password = $password;
		$this->name = $name;
	}

	public function getId(): CustomerId
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