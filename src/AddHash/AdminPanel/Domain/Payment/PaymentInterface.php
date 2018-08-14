<?php

namespace App\AddHash\AdminPanel\Domain\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGateway;
use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\User\User;

interface PaymentInterface
{
	public function createTransaction($externalId) : TransactionInterface;

	public function getPaymentMethod();

	public function getPaymentGateway();

	public function getCurrency();

	public function setPrice();

	public function getPrice();

	public function getUser() : User;

	public function setPaymentGateway(PaymentGatewayInterface $gateway);
}