<?php

namespace App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe;

use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Payment\PaymentInterface;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentGatewayStripe implements PaymentGatewayInterface
{
	const API_KEY_PRIVATE = 'sk_test_UUeNKCoiCH94euQA1F25elLg';
	const API_KEY_PUBLIC = 'pk_test_4d16pNNIAm3Chc7JFRkUOGM0';

	private $payment;

	public function __construct(PaymentInterface $payment)
	{
		$this->payment = $payment;
		Stripe::setApiKey(self::API_KEY_PRIVATE);
	}

	public function makePayment($params = [])
	{
		return Charge::create([
			'amount' => $this->payment->getPrice() * 100,
			'currency' => $this->payment->getCurrency(),
			'source' => $params['token']
		]);

	}

	public static function getPublicKey()
	{
		return self::API_KEY_PUBLIC;
	}



}