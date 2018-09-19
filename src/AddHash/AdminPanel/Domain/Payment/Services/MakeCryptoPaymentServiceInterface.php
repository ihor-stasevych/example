<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;

interface MakeCryptoPaymentServiceInterface
{
	public function execute(MakeCryptoPaymentCommandInterface $command);
}