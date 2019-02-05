<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class Phone
{
	/**
	 * @var string|null
	 */
	protected $phone;

	/**
	 * Phone constructor.
	 * @param string|null $phone
	 */
	public function __construct(?string $phone)
	{
		$this->setPhone($phone);
	}

	/**
	 * @return string|null
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	private function setPhone(?string $phone)
	{
		$this->phone = $phone;
	}

	public function __toString()
	{
		return $this->phone;
	}
}