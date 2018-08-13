<?php

namespace App\AddHash\AdminPanel\Domain\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\User\User;

class Payment implements PaymentInterface
{
	private $id;

	private $currency;

	private $price;

	private $user;

	private $paymentGateway;

	private $paymentMethod;

	public function __construct($price, $currency, $user, $id = null)
	{
	    $this->id = $id;
		$this->price = $price;
		$this->currency = $currency;
		$this->user = $user;
	}

    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function getPaymentGateway(): PaymentGatewayInterface
    {
        return $this->paymentGateway;
    }

    public function makePayment($params = [])
    {
        return $this->getPaymentGateway()->makePayment($params);
    }

    public function getUser(): User
    {
        return $this->user;
    }

	public function createTransaction($externalId): TransactionInterface
	{
		return new PaymentTransaction($this, $externalId);
	}

	public function setPrice($price = 0)
	{
		$this->price = $price;
	}

	public function setPaymentGateway(PaymentGatewayInterface $gateway)
	{
		$this->paymentGateway = $gateway;
	}
}