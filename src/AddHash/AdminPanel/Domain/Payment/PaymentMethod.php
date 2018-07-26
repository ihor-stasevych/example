<?php

namespace App\AddHash\AdminPanel\Domain\Payment;

class PaymentMethod
{
	private $id;

	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}
}