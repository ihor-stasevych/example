<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Repository;


use App\AddHash\AdminPanel\Domain\Payment\Payment;

interface PaymentRepositoryInterface
{
	public function findById($id);

	public function save(Payment $payment);
}