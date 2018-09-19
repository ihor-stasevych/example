<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Payment\PaymentTransaction;
use App\AddHash\System\GlobalContext\ValueObject\CryptoPayment;
use App\AddHash\AdminPanel\Domain\Payment\Gateway\PaymentGatewayInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\CantFindNewOrderErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Command\MakeCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\MakeCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\CantCreatePaymentErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentMethodRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\CantFindPaymentMethodErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;

class MakeCryptoPaymentService implements MakeCryptoPaymentServiceInterface
{
    const PAYMENT_METHOD_NAME = 'Crypto';

	private $paymentRepository;

	private $paymentMethodRepository;

	private $paymentTransactionRepository;

	private $orderRepository;

	private $paymentGateway;

	public function __construct(
		PaymentRepositoryInterface $paymentRepository,
		PaymentMethodRepositoryInterface $paymentMethodRepository,
		PaymentTransactionRepositoryInterface $paymentTransactionRepository,
		StoreOrderRepositoryInterface $orderRepository,
        PaymentGatewayInterface $paymentGateway
	)
	{
		$this->paymentRepository = $paymentRepository;
		$this->paymentMethodRepository = $paymentMethodRepository;
		$this->paymentTransactionRepository = $paymentTransactionRepository;
		$this->orderRepository = $orderRepository;
		$this->paymentGateway = $paymentGateway;

	}

    /**
     * @param MakeCryptoPaymentCommandInterface $command
     * @return CryptoPayment
     * @throws CantCreatePaymentErrorException
     * @throws CantFindNewOrderErrorException
     * @throws CantFindPaymentMethodErrorException
     */
	public function execute(MakeCryptoPaymentCommandInterface $command)
	{
		/** @var StoreOrder $order */
		$order = $this->orderRepository->findById($command->getOrderId());

		if (null === $order) {
			throw new CantFindNewOrderErrorException('Cant find new order');
		}

		$method = $this->paymentMethodRepository->getByName(static::PAYMENT_METHOD_NAME);

		if (null === $method) {
			throw new CantFindPaymentMethodErrorException('Cant find payment method');
		}

		$payment = new Payment(0, $command->getCurrency(), $order->getUser());
		$payment->setPaymentMethod($method);
		$payment->setPaymentGateway($this->paymentGateway);

		/** @var CryptoPayment $cryptoPayment */
		$cryptoPayment = $payment->createPayment($order, [
		    'currency' => $command->getCurrency(),
        ]);

		if (null === $cryptoPayment) {
			throw new CantCreatePaymentErrorException('Cant create payment');
		}

		$payment->setPrice($cryptoPayment->getPrice());

		$transaction = new PaymentTransaction($payment, $cryptoPayment->getInvoice());

		$this->paymentRepository->save($payment);
		$this->paymentTransactionRepository->save($transaction);

		return $cryptoPayment;
	}
}