<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class CryptoPayment
{
	protected $invoice;

	protected $address;


	public function __construct($invoice, $address)
	{
		$this->invoice = $invoice;
		$this->address = $address;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function getInvoice()
	{
		return $this->invoice;
	}

	public function __toString()
	{
		return $this->address;
	}
}