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

	/**
	 * @var string
	 * @Assert\NotNull()
	 */
	private $amount;

	/**
	 * MakeCryptoPaymentCommand constructor.
	 *
	 * @param $currency
	 * @param $amount
	 */
	public function __construct($currency, $amount)
	{
		$this->currency = $currency;
		$this->amount = $amount;

	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function getAmount()
	{
		return $this->amount;
	}
}