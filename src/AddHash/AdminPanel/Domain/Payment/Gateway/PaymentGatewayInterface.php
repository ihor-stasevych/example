<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Gateway;

interface PaymentGatewayInterface
{
	public function makePayment($params = []);
	public function getGateWayName();
}