<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakePaymentForOrderServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Stripe\PaymentGatewayStripe;
use Stripe\Charge;

class MakePaymentForOrderService implements MakePaymentForOrderServiceInterface
{
	private $paymentRepository;
	private $paymentTransactionRepository;

	public function __construct(
		PaymentRepositoryInterface $paymentRepository,
		PaymentTransactionRepositoryInterface $paymentTransactionRepository
	)
	{
		$this->paymentRepository = $paymentRepository;
		$this->paymentTransactionRepository = $paymentTransactionRepository;
	}

	public function execute($token, $amount, $user)
	{
		$payment = new Payment($amount, 'usd', $user);
		$payment->setPaymentGateway(new PaymentGatewayStripe($payment));

		/** @var Charge $charge */
		$charge = $payment->makePayment(['token' => $token]);

		$transaction = new PaymentTransaction($payment, $charge['id']);

		if ($charge['captured']) {
			$transaction->processTransaction();
		}

		$this->paymentRepository->save($payment);
		$this->paymentTransactionRepository->save($transaction);

		return true;
	}
}