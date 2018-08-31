<?php

namespace App\AddHash\AdminPanel\Domain\Payment\Services;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;

interface MakeCryptoPaymentServiceInterface
{
	public function execute(StoreOrder $order, $amount, $currency);
}