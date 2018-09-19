<?php

namespace App\AddHash\AdminPanel\Application\Command\Payments;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;

class MakeCryptoPaymentCommand implements MakeCryptoPaymentCommandInterface
{
    /**
     * @var string
     * @Assert\NotNull()
     */
    private $orderId;

	/**
	 * @var string
	 * @Assert\NotNull()
	 */
	private $currency;

	public function __construct($orderId, $currency)
	{
	    $this->orderId = $orderId;
		$this->currency = $currency;
	}

	public function getOrderId(): int
    {
        return $this->orderId;
    }

	public function getCurrency(): string
	{
		return $this->currency;
	}
}