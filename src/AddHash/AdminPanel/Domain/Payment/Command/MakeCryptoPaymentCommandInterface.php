<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Command;

interface MakeCryptoPaymentCommandInterface
{
    public function getOrderId(): int;

	public function getCurrency(): string;
}