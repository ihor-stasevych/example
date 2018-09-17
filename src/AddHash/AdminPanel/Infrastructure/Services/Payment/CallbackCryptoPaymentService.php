<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Payment\Payment;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidOrderErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidInvoiceErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\WaitingConfirmationsException;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidInputDataErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Command\CallbackCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\CallbackCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;

class CallbackCryptoPaymentService implements CallbackCryptoPaymentServiceInterface
{
    private $storeOrderRepository;

    private $paymentTransactionRepository;

    public function __construct(
        StoreOrderRepositoryInterface $storeOrderRepository,
        PaymentTransactionRepositoryInterface $paymentTransactionRepository
    )
    {
        $this->storeOrderRepository = $storeOrderRepository;
        $this->paymentTransactionRepository = $paymentTransactionRepository;
    }

    /**
     * @param CallbackCryptoPaymentCommandInterface $command
     * @return mixed
     * @throws InvalidInputDataErrorException
     * @throws InvalidInvoiceErrorException
     * @throws InvalidOrderErrorException
     * @throws WaitingConfirmationsException
     */
    public function execute(CallbackCryptoPaymentCommandInterface $command): string
    {
        $orderId = $command->getOrderId();
        /** @var StoreOrder $order */
        $order = $this->storeOrderRepository->findNewById($orderId);

        if (null === $order) {
            throw new InvalidOrderErrorException('Invalid order');
        }

        $inputData = json_decode($command->getInputData());

        if (null === $inputData || !$inputData) {
            throw new InvalidInputDataErrorException('Invalid input data');
        }

        //save confirmations and maxConfirmations to DB

        if ($inputData->confirmations < $inputData->maxConfirmations) {
            throw new WaitingConfirmationsException('Waiting for confirmations');
        }

        $invoice = $inputData->invoice;

        $paymentTransaction = $this->paymentTransactionRepository->findByExternalId($invoice);

        if (null === $paymentTransaction) {
            throw new InvalidInvoiceErrorException('Invalid invoice');
        }

        /** @var Payment $payment */
        $payment = $paymentTransaction->getPayment();

        if ($payment->getUser()->getId() != $order->getUser()->getId()) {
            throw new InvalidInvoiceErrorException('Invalid invoice');
        }

        //$amountPaid = $inputData->inTransaction->amount / pow(10, $inputData->inTransaction->exp);

        $order->setPayedState();
        $order->setPayment($payment);
        $this->storeOrderRepository->save($order);

        return $invoice;
    }
}