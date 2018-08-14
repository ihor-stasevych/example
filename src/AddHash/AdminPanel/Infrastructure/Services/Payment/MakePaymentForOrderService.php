<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentMethodRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakePaymentForOrderServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use Stripe\Charge;

class MakePaymentForOrderService implements MakePaymentForOrderServiceInterface
{
	private $paymentRepository;
	private $paymentMethodRepository;
	private $paymentTransactionRepository;

	public function __construct(
		PaymentRepositoryInterface $paymentRepository,
		PaymentMethodRepositoryInterface $paymentMethodRepository,
		PaymentTransactionRepositoryInterface $paymentTransactionRepository
	)
	{
		$this->paymentRepository = $paymentRepository;
		$this->paymentMethodRepository = $paymentMethodRepository;
		$this->paymentTransactionRepository = $paymentTransactionRepository;
	}

	/**
	 * @param $token
	 * @param $amount
	 * @param $user
	 * @return Payment
	 * @throws \Exception
	 */
	public function execute($token, $amount, $user)
	{
		$method = $this->paymentMethodRepository->getByName('CreditCard');

		if (!$method) {
			throw new \Exception('Cant find payment method');
		}

		$payment = new Payment($amount, 'usd', $user);
		$payment->setPaymentMethod($method);
		$payment->setPaymentGateway(new PaymentGatewayStripe($payment));

		/** @var Charge $charge */
		$charge = $payment->makePayment(['token' => $token]);

		$transaction = new PaymentTransaction($payment, $charge['id']);

		if ($charge['captured']) {
			$transaction->processTransaction();
		}

		$this->paymentRepository->save($payment);
		$this->paymentTransactionRepository->save($transaction);

		return $payment;
	}
}