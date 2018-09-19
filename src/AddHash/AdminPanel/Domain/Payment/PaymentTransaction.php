<?php

namespace App\AddHash\AdminPanel\Domain\Payment;


class PaymentTransaction implements TransactionInterface
{
	const STATE_NEW = 1;

	const STAT_PROCESSED = 2;

	const DEFAULT_CONFIRMATION = 0;

	const DEFAULT_MAX_CONFIRMATION = 0;


	private $id;

	private $externalId;

	private $payment;

	private $state;

	private $createdAt;

	private $amount;

    private $confirmation = self::DEFAULT_CONFIRMATION;

    private $maxConfirmation = self::DEFAULT_MAX_CONFIRMATION;

	public function __construct(PaymentInterface $payment, $externalId)
	{
		$this->externalId = $externalId;
		$this->payment = $payment;
		$this->state = self::STATE_NEW;
		$this->amount = $payment->getPrice();
		$this->createdAt = new \DateTime();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function getPayment(): PaymentInterface
	{
		return $this->payment;
	}

    public function getConfirmation(): int
    {
        return $this->confirmation;
    }

    public function getMaxConfirmation(): int
    {
        return $this->maxConfirmation;
    }

    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }

    public function setMaxConfirmation($maxConfirmation)
    {
        $this->maxConfirmation = $maxConfirmation;
    }

	public function processTransaction()
	{
		$this->state = self::STAT_PROCESSED;
	}
}