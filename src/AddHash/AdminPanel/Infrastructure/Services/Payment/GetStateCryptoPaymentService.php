<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidOrderErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Command\GetStateCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetStateCryptoPaymentServiceInterface;
use App\AddHash\AdminPanel\Domain\Payment\Repository\PaymentTransactionRepositoryInterface;

class GetStateCryptoPaymentService implements GetStateCryptoPaymentServiceInterface
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
     * @param GetStateCryptoPaymentCommandInterface $command
     * @return array
     * @throws InvalidOrderErrorException
     */
    public function execute(GetStateCryptoPaymentCommandInterface $command): array
    {
        $orderId = $command->getOrderId();

        $this->logger->info('Start Get state crypto payment order # ' . $orderId);

        /** @var StoreOrder $order */
        $order = $this->storeOrderRepository->findById($orderId);

        if (null === $order) {
            $this->logger->error('Get state crypto payment invalid order # ' . $orderId);
            throw new InvalidOrderErrorException('Invalid order');
        }

        $payment = $order->getPayment();

        $confirmation = null;
        $maxConfirmation = null;

        $data = ['success' => false];

        if (null !== $payment) {
            $paymentTransaction = $this->paymentTransactionRepository->findByPaymentId($payment->getId());

            $confirmation = $paymentTransaction->getConfirmation();
            $maxConfirmation = $paymentTransaction->getMaxConfirmation();

            if (null !== $confirmation) {
                if (null !== $maxConfirmation && $confirmation >= $maxConfirmation) {
                    $data['success'] = true;
                }

                $data['confirmations'] = $confirmation;
            }
        }

        $this->logger->info('Finish Get state crypto payment order# ' . $orderId, $data);

        return $data;
    }
}