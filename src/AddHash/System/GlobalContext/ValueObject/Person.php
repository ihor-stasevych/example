<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class Person
{
	/**
	 * @var null|string
	 */
	protected $firstName;

	/**
	 * @var null|string
	 */
	protected $lastName;

	/**
	 * @var null|string
	 */
	protected $phone;

	/**
	 * Person constructor.
	 *
	 * @param null|string $firstName
	 * @param null|string $lastName
	 * @param null|string $phone
	 */
	public function __construct(?string $firstName, ?string $lastName, ?string $phone)
	{
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setLastName($phone);
	}

	/**
	 * @return null|string
	 */
	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	/**
	 * @param null|string $firstName
	 */
	protected function setFirstName(?string $firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	 * @return null|string
	 */
	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	/**
	 * @param null|string $lastName
	 */
	protected function setLastName(?string $lastName)
	{
		$this->lastName = $lastName;
	}

	/**
	 * @param null|string $phone
	 */
	protected function setPhone(?string $phone)
	{
		$this->phone = $phone;
	}
}