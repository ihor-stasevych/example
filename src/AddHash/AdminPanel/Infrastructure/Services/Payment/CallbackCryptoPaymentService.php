<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use Psr\Log\LoggerInterface;
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

    private $logger;

    public function __construct(
        StoreOrderRepositoryInterface $storeOrderRepository,
        PaymentTransactionRepositoryInterface $paymentTransactionRepository,
        LoggerInterface $logger
    )
    {
        $this->storeOrderRepository = $storeOrderRepository;
        $this->paymentTransactionRepository = $paymentTransactionRepository;
        $this->logger = $logger;
    }

    /**
     * @param CallbackCryptoPaymentCommandInterface $command
     * @return mixed
     * @throws InvalidInputDataErrorException
     * @throws InvalidInvoiceErrorException
     * @throws InvalidOrderErrorException
     */
    public function execute(CallbackCryptoPaymentCommandInterface $command): string
    {
        $orderId = $command->getOrderId();

        /** @var StoreOrder $order */
        $order = $this->storeOrderRepository->findNewById($orderId);

        if (null === $order) {
            $this->logger->error('Callback crypto payment invalid order # ' . $orderId);

            throw new InvalidOrderErrorException('Invalid order');
        }

        $inputData = json_decode($command->getInputData());

        if (null === $inputData ||
            !$inputData ||
            !isset($inputData->confirmations) ||
            !isset($inputData->maxConfirmations) ||
            empty($inputData->invoice)
        ) {
            $this->logger->error('Callback crypto payment invalid input data order # ' . $orderId, (array) $inputData);

            throw new InvalidInputDataErrorException('Invalid input data');
        }

        $paymentTransaction = $this->paymentTransactionRepository->findByExternalId($inputData->invoice);

        if (null === $paymentTransaction) {
            $this->logger->error('Callback crypto payment invalid invoice order # ' . $orderId, (array) $inputData);

            throw new InvalidInvoiceErrorException('Invalid invoice');
        }

        $paymentTransaction->setConfirmation($inputData->confirmations);
        $paymentTransaction->setMaxConfirmation($inputData->maxConfirmations);
        $this->paymentTransactionRepository->save($paymentTransaction);

        /** @var Payment $payment */
        $payment = $paymentTransaction->getPayment();

        if ($payment->getUser()->getId() != $order->getUser()->getId()) {
            $this->logger->error('Callback crypto payment invalid invoice order # ' . $orderId, (array) $inputData);

            throw new InvalidInvoiceErrorException('Invalid invoice');
        }

        //$amountPaid = $inputData->inTransaction->amount / pow(10, $inputData->inTransaction->exp);

        if ($inputData->confirmations < $inputData->maxConfirmations) {
            $this->logger->info('Callback crypto payment - Waiting for confirmations order # ' . $orderId, [
                'confirmation'    => $inputData->confirmations,
                'maxConfirmation' => $inputData->maxConfirmations,
            ]);

            die("waiting for confirmations");
            //throw new WaitingConfirmationsException('Waiting for confirmations');
        }

        $order->setPayedState();
        $order->setPayment($payment);
        $this->storeOrderRepository->save($order);

        return $inputData->invoice;
    }
}