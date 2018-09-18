<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Command;

interface GetStateCryptoPaymentCommandInterface
{
	public function getOrderId(): int;
}