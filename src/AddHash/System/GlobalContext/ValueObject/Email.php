<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class Email
{
	/** @var string */
	protected $email;

	/**
	 * Email constructor.
	 * @param string $email
	 */
	public function __construct(string $email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	public function __toString()
	{
		return $this->email;
	}

}