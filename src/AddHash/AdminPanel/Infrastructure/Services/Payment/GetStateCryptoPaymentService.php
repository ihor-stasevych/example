<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidOrderErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Command\GetStateCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetStateCryptoPaymentServiceInterface;

class GetStateCryptoPaymentService implements GetStateCryptoPaymentServiceInterface
{
    private $storeOrderRepository;

    private $logger;

    public function __construct(
        StoreOrderRepositoryInterface $storeOrderRepository,
        LoggerInterface $logger
    )
    {
        $this->storeOrderRepository = $storeOrderRepository;
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

        $confirmation = $order->getConfirmation();
        $maxConfirmation = $order->getMaxConfirmation();

        $this->logger->info('Get state crypto payment confirmations order# ' . $orderId, [
            'confirmation'    => $confirmation,
            'maxConfirmation' => $maxConfirmation,
        ]);

        $data = [
            'success'       => true,
            'confirmations' => $confirmation,
        ];

        if ($confirmation < $maxConfirmation || $maxConfirmation == 0) {
            $data['success'] = false;
        }

        $this->logger->info('Finish Get state crypto payment order# ' . $orderId, $data);

        return $data;
    }
}