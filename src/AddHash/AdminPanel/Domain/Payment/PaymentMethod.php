<?php

namespace App\AddHash\AdminPanel\Domain\Payment;

use Doctrine\Common\Collections\ArrayCollection;

class PaymentMethod
{
	private $id;

	private $name;

	private $payment;

	public function __construct($name)
	{
		$this->name = $name;
		$this->payment = new ArrayCollection();
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