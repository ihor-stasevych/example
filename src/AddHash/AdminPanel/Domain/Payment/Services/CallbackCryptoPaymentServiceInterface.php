<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Payment\Command\CallbackCryptoPaymentCommandInterface;

interface CallbackCryptoPaymentServiceInterface
{
	public function execute(CallbackCryptoPaymentCommandInterface $command): string;
}