<?php

namespace App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;

class PaymentGatewayPayBear implements PaymentGatewayInterface
{
	const API_KEY_SECRET = 'sec132369cd45cc3110707c785513c80d05';
	const API_KEY_SECRET_TEST = 'secaba806820dac7678dfbf72bf2d97aabf';
	const API_HOST = 'http://dev.addhash.com';

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


		if ($response = @file_get_contents($url)) {
			$response = json_decode($response);

			if (!$response->success) {
				return null;
			}

			$currencies = $this->getCurrencies();
			$coinValue = $this->getCryptoAmountByUsd($order->getItemsPriceTotal(), $currency);
			$currencyData = $currencies[$currency] ?? [];

			$cryptoPayment =  new CryptoPayment(
				$response->data->invoice,
				$response->data->address, $currencyData['title'],
				$currencyData['code'], $currencyData['icon'],
				$currencyData['rate'], $currencyData['maxConfirmations'],
				$coinValue, self::API_HOST . '/api/v1/payments/crypto/new' . $currency,
				self::API_HOST . '/api/v1/payments/crypto/state'
			);


			return $cryptoPayment;

		}

		return null;
	}

	/**
	 * @return array|null
	 */
	public function getCurrencies()
	{
		$url = sprintf('https://api.paybear.io/v2/currencies?token=%s', self::API_KEY_SECRET);

		if ($response = file_get_contents($url)) {
			$response = json_decode($response, true);
			if (!$response['success']) {
				return null;
			}
		}

		foreach ($response['data'] as $currency => &$data) {
			$response['data'][$currency]['currencyUrl'] = self::API_HOST . '/api/v1/payments/crypto/new/' . $currency;
		}

		return $response['data'];
	}

	/**
	 * @param $usdAmount
	 * @param string $currency
	 * @return float|int
	 */
	public function getCryptoAmountByUsd($usdAmount, $currency = 'btc')
	{
		$url = 'https://api.paybear.io/v2/'. $currency .'/exchange/usd/rate';

		if ($response = file_get_contents($url)) {
			$response = json_decode($response);

			if ($response->success) {
				return  $usdAmount / $response->data->mid;
			}
		}

		return 0;
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