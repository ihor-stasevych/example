<?php

namespace App\AddHash\System\GlobalContext\ValueObject;

class CryptoPayment
{
	protected $invoice;

	protected $address;

	protected $title;

	protected $code;

	protected $icon;

	protected $rate;

	protected $maxConfirmations;

	protected $coinsValue;

	protected $currencyUrl;

	protected $statusUrl;


	public function __construct(
		$invoice, $address, $title,
		$code, $icon, $rate, $maxConfirm,
		$coinsValue, $currencyUrl,
		$statusUrl
	)
	{
		$this->invoice = $invoice;
		$this->address = $address;
		$this->title = $title;
		$this->code = $code;
		$this->icon = $icon;
		$this->rate = $rate;
		$this->maxConfirmations = $maxConfirm;
		$this->coinsValue = $coinsValue;
		$this->currencyUrl = $currencyUrl;
		$this->statusUrl = $statusUrl;
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

	public function getPrice()
	{
		return $this->coinsValue;
	}

	public function getData()
	{
		return [
			'address' => $this->address, 'title' => $this->title,
			'code' => $this->code, 'icon' => $this->icon, 'rate' => $this->rate,
			'maxConfirmations' => $this->maxConfirmations, 'coinsValue' => $this->coinsValue,
			'currencyUrl' => $this->currencyUrl, 'statusUrl' => $this->statusUrl
		];
	}
}