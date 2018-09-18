<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Payment;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Payment\Exceptions\InvalidOrderErrorException;
use App\AddHash\AdminPanel\Domain\Payment\Command\GetStateCryptoPaymentCommandInterface;
use App\AddHash\AdminPanel\Domain\Payment\Services\GetStateCryptoPaymentServiceInterface;

class GetStateCryptoPaymentService implements GetStateCryptoPaymentServiceInterface
{
    private $storeOrderRepository;

    public function __construct(StoreOrderRepositoryInterface $storeOrderRepository)
    {
        $this->storeOrderRepository = $storeOrderRepository;
    }

    /**
     * @param GetStateCryptoPaymentCommandInterface $command
     * @return array
     * @throws InvalidOrderErrorException
     */
    public function execute(GetStateCryptoPaymentCommandInterface $command): array
    {
        $orderId = $command->getOrderId();
        /** @var StoreOrder $order */
        $order = $this->storeOrderRepository->findById($orderId);

        if (null === $order) {
            throw new InvalidOrderErrorException('Invalid order');
        }

        $confirmation = $order->getConfirmation();
        $maxConfirmation = $order->getMaxConfirmation();

        $data = [
            'success'       => true,
            'confirmations' => $confirmation,
        ];

        if ($confirmation < $maxConfirmation || $maxConfirmation == 0) {
            $data['success'] = false;
        }

        return $data;
    }
}