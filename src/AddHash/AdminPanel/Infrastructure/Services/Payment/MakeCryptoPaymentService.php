<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;


use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentMethodRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear\PaymentGatewayPayBear;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;

class MakeCryptoPaymentService implements MakeCryptoPaymentServiceInterface
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
	 * @param StoreOrder $order
	 * @param $amount
	 * @param $currency
	 * @throws \Exception
	 */
	public function execute(StoreOrder $order, $amount, $currency)
	{
		$method = $this->paymentMethodRepository->getByName('Crypto');

		if (!$method) {
			throw new \Exception('Cant find payment method');
		}

		$payment = new Payment($amount, $currency, $order->getUser());
		$payment->setPaymentMethod($method);
		$payment->setPaymentGateway(new PaymentGatewayPayBear());

		/** @var CryptoPayment $cryptoPayment */
		$cryptoPayment = $payment->createPayment($order, ['currency' => $currency]);

		if (!$cryptoPayment){
			throw new \Exception('Cant create PayBear payment');
		}

		$transaction = new PaymentTransaction($payment, $cryptoPayment->getInvoice());

		$this->paymentRepository->save($payment);
		$this->paymentTransactionRepository->save($transaction);

	}
}