<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear\PaymentGatewayPayBear;

class GetCryptoCurrenciesService implements GetCryptoCurrenciesServiceInterface
{

	public function execute()
	{
		$payBear = new PaymentGatewayPayBear();
		return $payBear->getCurrencies();
	}
}