<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Payment\Command\GetStateCryptoPaymentCommandInterface;

interface GetStateCryptoPaymentServiceInterface
{
	public function execute(GetStateCryptoPaymentCommandInterface $command): array;
}