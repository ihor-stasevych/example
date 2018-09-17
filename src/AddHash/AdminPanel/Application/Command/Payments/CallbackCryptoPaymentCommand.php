<?php

namespace App\AddHash\AdminPanel\Application\Command\Payments;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\Payment\Command\CallbackCryptoPaymentCommandInterface;

class CallbackCryptoPaymentCommand implements CallbackCryptoPaymentCommandInterface
{
	/**
	 * @var string
     * @Assert\NotBlank()
	 */
	private $orderId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
	private $inputData;

	public function __construct($orderId, $inputData)
	{
        $this->orderId = $orderId;
        $this->inputData = $inputData;
	}

	public function getOrderId(): int
	{
		return $this->orderId;
	}

	public function getInputData(): string
    {
        return $this->inputData;
    }
}