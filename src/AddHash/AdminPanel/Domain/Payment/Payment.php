<?php

namespace App\AddHash\AdminPanel\Domain\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGateway;
use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\User\User;

class Payment implements PaymentInterface
{
	private $id;

	private $currency;

	private $price;

	private $user;

	private $paymentGateway;

	private $paymentMethod;

	private $createdAt;

	private $gatewayName;

	public function __construct($price = 0, $currency, $user, $id = null)
	{
	    $this->id = $id;
		$this->price = $price;
		$this->currency = $currency;
		$this->user = $user;
		$this->createdAt = new \DateTime();
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

    public function createPayment(StoreOrder $order, $params  = [])
    {
		return $this->getPaymentGateway()->createPayment($order, $params);
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
		$this->gatewayName = $this->paymentGateway->getGateWayName();
	}

	public function setPaymentMethod(PaymentMethod $paymentMethod)
	{
		$this->paymentMethod = $paymentMethod;
	}
}