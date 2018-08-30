<?php

namespace App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;

class PaymentGatewayPayBear implements PaymentGatewayInterface
{

	public function makePayment($params = [])
	{
		// TODO: Implement makePayment() method.
	}

	public function getGateWayName()
	{
		return 'PayBear';
	}
}