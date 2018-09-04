<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;


use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentMethodRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Payment\Gateway\Paybear\PaymentGatewayPayBear;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;

class MakeCryptoPaymentService implements MakeCryptoPaymentServiceInterface
{
	private $paymentRepository;
	private $paymentMethodRepository;
	private $paymentTransactionRepository;
	private $orderRepository;

	public function __construct(
		PaymentRepositoryInterface $paymentRepository,
		PaymentMethodRepositoryInterface $paymentMethodRepository,
		PaymentTransactionRepositoryInterface $paymentTransactionRepository,
		StoreOrderRepositoryInterface $orderRepository
	)
	{
		$this->paymentRepository = $paymentRepository;
		$this->paymentMethodRepository = $paymentMethodRepository;
		$this->paymentTransactionRepository = $paymentTransactionRepository;
		$this->orderRepository = $orderRepository;
	}

	/**
	 * @param User $user
	 * @param MakeCryptoPaymentCommandInterface $command
	 * @return CryptoPayment
	 * @throws \Exception
	 */
	public function execute(User $user, MakeCryptoPaymentCommandInterface $command)
	{
		$order = $this->orderRepository->findNewByUserId($user->getId());

		if (!$order) {
			throw new \Exception('Cant find new order');
		}

		$method = $this->paymentMethodRepository->getByName('Crypto');

		if (!$method) {
			throw new \Exception('Cant find payment method');
		}

		$payment = new Payment($command->getAmount(), $command->getCurrency(), $user);
		$payment->setPaymentMethod($method);
		$payment->setPaymentGateway(new PaymentGatewayPayBear());

		/** @var CryptoPayment $cryptoPayment */
		$cryptoPayment = $payment->createPayment($order, ['currency' => $command->getCurrency()]);

		if (!$cryptoPayment){
			throw new \Exception('Cant create PayBear payment');
		}

		$transaction = new PaymentTransaction($payment, $cryptoPayment->getInvoice());

		$this->paymentRepository->save($payment);
		$this->paymentTransactionRepository->save($transaction);

		return $cryptoPayment;

	}
}