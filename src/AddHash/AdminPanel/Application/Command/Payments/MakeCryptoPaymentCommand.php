<?php

namespace App\AddHash\AdminPanel\Application\Command\Payments;

use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MakeCryptoPaymentCommand implements MakeCryptoPaymentCommandInterface
{
	/**
	 * @var string
	 * @Assert\NotNull()
	 */
	private $currency;


	public function __construct($currency)
	{
		$this->currency = $currency;

	}

	public function getCurrency()
	{
		return $this->currency;
	}
}