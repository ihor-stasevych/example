<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class Phone
{
	/**
	 * @var string
	 */
	protected $phone;

	/**
	 * Phone constructor.
	 * @param string $phone
	 */
	public function __construct(?string $phone)
	{
		$this->setPhone($phone);
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
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
		return $this->getPhone();
	}
}