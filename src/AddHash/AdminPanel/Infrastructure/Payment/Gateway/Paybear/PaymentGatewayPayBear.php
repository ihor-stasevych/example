<?php

namespace App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;

class PaymentGatewayPayBear implements PaymentGatewayInterface
{
	const API_KEY_SECRET = 'sec132369cd45cc3110707c785513c80d05';

	/**
	 * @param StoreOrder $order
	 * @param array $params
	 * @return CryptoPayment|null
	 */
	public function createPayment(StoreOrder $order, $params = []):? CryptoPayment
	{
		$currency = $params['currency'];
		$callbackUrl = 'http://dev.addhash.com/api/v1/payments/order/' . $order->getId();
		$url = sprintf('https://api.paybear.io/v2/%s/payment/%s?token=%s', $currency, urlencode($callbackUrl), self::API_KEY_SECRET);

		if ($response = file_get_contents($url)) {
			$response = json_decode($response);
			if (!$response->success) {
				return null;
			}

			return new CryptoPayment(
				$response->data->invoice,
				$response->data->address
			);
		}

		return null;
	}

	public function makePayment($params = [])
	{
		// TODO: Implement makePayment() method.
	}

	public function getGateWayName()
	{
		return 'PayBear';
	}
}