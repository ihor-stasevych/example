<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Payment\Command\GetCurrenciesCryptoPaymentCommandInterface;

interface GetCryptoCurrenciesServiceInterface
{
	public function execute(GetCurrenciesCryptoPaymentCommandInterface $command);
}