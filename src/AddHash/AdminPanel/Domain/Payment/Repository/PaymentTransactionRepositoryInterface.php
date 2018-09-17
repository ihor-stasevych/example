<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Repository;


use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;

interface PaymentTransactionRepositoryInterface
{
	public function findById($id);

    public function findByExternalId(string $externalId): ?PaymentTransaction;

	public function save(PaymentTransaction $paymentTransaction);
}