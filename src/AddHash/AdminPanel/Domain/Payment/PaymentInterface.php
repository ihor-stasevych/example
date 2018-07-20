<?php

namespace App\AddHash\AdminPanel\Domain\Payment;


interface PaymentInterface
{
	public function createTransaction() : TransactionInterface;

	public function getPaymentMethod();

	public function getPaymentType();

	public function getPaymentGateway();

	public function getCurrency();

	public function setPrice();
}