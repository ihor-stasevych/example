<?php

namespace App\AddHash\AdminPanel\Application\Command\Payments;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Payment\Command\GetStateCryptoPaymentCommandInterface;

class GetStateCryptoPaymentCommand implements GetStateCryptoPaymentCommandInterface
{
	/**
	 * @var string
     * @Assert\NotBlank()
	 */
	private $orderId;

	public function __construct($orderId)
	{
        $this->orderId = $orderId;
	}

	public function getOrderId(): int
	{
		return $this->orderId;
	}
}