<?php

namespace App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;
use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;

class PaymentGatewayPayBear implements PaymentGatewayInterface
{
    const API_KEY_SECRET = 'sec132369cd45cc3110707c785513c80d05';

    const API_KEY_SECRET_TEST = 'secaba806820dac7678dfbf72bf2d97aabf';


    private $urlGenerator;

    private $requestStack;

    private $host;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;

        $host = $this->requestStack->getCurrentRequest()->getHost();
        $schema = $this->requestStack->getCurrentRequest()->getScheme();

        $this->host = $schema . '://' . $host;
    }

	/**
	 * @param StoreOrder $order
	 * @param array $params
	 * @return CryptoPayment|null
	 */
	public function createPayment(StoreOrder $order, $params = []): ?CryptoPayment
	{
	    $orderId = $order->getId();
	    $currencies = $this->getCurrencies($orderId);
        $currency = $params['currency'];

	    if (true === empty($currencies[$currency])) {
	        return null;
        }

		$callbackUrl = $this->host . $this->urlGenerator->generate('payments.crypto.callback', [
            'orderId' => $orderId,
        ]);

		$url = sprintf('https://api.paybear.io/v2/%s/payment/%s?token=%s', $currency, urlencode($callbackUrl), static::API_KEY_SECRET);

		if ($response = @file_get_contents($url)) {
			$response = json_decode($response);

			if (!$response->success) {
				return null;
			}

			$coinValue = $this->getCryptoAmountByUsd($order->getItemsPriceTotal(), $currency);
			$currencyData = $currencies[$currency];

			$cryptoPayment =  new CryptoPayment(
				$response->data->invoice,
				$response->data->address,
                $currencyData['title'],
				$currencyData['code'],
                $currencyData['icon'],
				$currencyData['rate'],
                $currencyData['maxConfirmations'],
				$coinValue,
                $this->urlGenerator->generate('payments.crypto', [
                    'orderId'  => $orderId,
                    'currency' => $currency,
                ]),
                $this->urlGenerator->generate('payments.crypto.state', [
                    'orderId' => $orderId,
                ])
			);

			return $cryptoPayment;
		}

		return null;
	}

    /**
     * @param int $orderId
     * @return null|array
     */
	public function getCurrencies(int $orderId)
	{
		$url = sprintf('https://api.paybear.io/v2/currencies?token=%s', static::API_KEY_SECRET);

		if ($response = file_get_contents($url)) {
			$response = json_decode($response, true);
			if (!$response['success']) {
				return null;
			}
		}

		foreach ($response['data'] as $currency => &$data) {
            $response['data'][$currency]['currencyUrl'] = $this->urlGenerator->generate('payments.crypto', [
                'orderId'  => $orderId,
                'currency' => $currency,
            ]);
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