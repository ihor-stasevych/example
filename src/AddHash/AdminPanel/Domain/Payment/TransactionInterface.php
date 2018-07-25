<?php

namespace App\AddHash\AdminPanel\Domain\Payment;


interface TransactionInterface
{
	public function getAmount();
	public function getPayment(): PaymentInterface;
}