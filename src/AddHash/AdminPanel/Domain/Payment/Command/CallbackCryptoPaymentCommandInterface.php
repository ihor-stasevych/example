<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Command;

interface CallbackCryptoPaymentCommandInterface
{
	public function getOrderId(): int;

    public function getInputData(): string;
}