<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Gateway;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface PaymentGatewayInterface
{
	public function makePayment($params = []);

	public function createPayment(StoreOrder $order, $params = []);

	public function getGateWayName();
}