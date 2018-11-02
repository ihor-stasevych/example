<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Payment\PaymentMethod;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnPaidErrorException;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderUnReserveMinerServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnReserveMinersErrorException;
use App\AddHash\AdminPanel\Infrastructure\Services\Payment\MakeCryptoPaymentService;

class StoreOrderUnReserveMinerService implements StoreOrderUnReserveMinerServiceInterface
{
    /** 25 minutes */
    private const RESERVE_TIME = 900;

    /** 1.5 hour */
    private const RESERVE_TIME_CRYPTO = 5400;


    private $storeOrderRepository;

    private $minerStockRepository;

    private $notificationService;

	public function __construct(
	    StoreOrderRepositoryInterface $storeOrderRepository,
        MinerStockRepositoryInterface $minerStockRepository,
        SendUserNotificationServiceInterface $notificationService
    )
	{
        $this->storeOrderRepository = $storeOrderRepository;
        $this->minerStockRepository = $minerStockRepository;
        $this->notificationService = $notificationService;
	}

    /**
     * @return array
     * @throws StoreOrderNoUnPaidErrorException
     * @throws StoreOrderNoUnReserveMinersErrorException
     */
	public function execute(): array
	{
        $dataTime = new \DateTime();
        $nowDataTime = clone $dataTime;
        $dataTime->setTimestamp($dataTime->getTimestamp() - static::RESERVE_TIME);
	    $unPaidOrders = $this->storeOrderRepository->getNewByTime($dataTime);

	    if (!$unPaidOrders) {
            throw new StoreOrderNoUnPaidErrorException('No un paid orders');
        }

        $unReserveMiners = [];

	    /** @var  StoreOrder $unPaidOrder */
        foreach ($unPaidOrders as $unPaidOrder) {
            if (true === $this->isNotUnPaidCrypto($unPaidOrder, $nowDataTime)) {
                break;
            }

            $items = $unPaidOrder->getItems();
            $unPaidOrder->closeOrder();

            /** @var StoreOrderItem $item **/
            foreach ($items as $item) {
                $quantity = $item->getQuantity();

                for ($i = 0; $i < $quantity; $i++) {
                    $minerStock = $item->getProduct()->unReserveMiner();

                    if (!$minerStock) {
                        break;
                    }

                    $unReserveMiners[] = $minerStock;
                    $this->minerStockRepository->save($minerStock);
                }
            }

            $title = 'System notification';
            $message = 'Order #' . $unPaidOrder->getId() . ' was closed';

            $this->notificationService->execute($title, $message, $unPaidOrder->getUser());

            $this->storeOrderRepository->save($unPaidOrder);
        }

        if (!$unReserveMiners) {
            throw new StoreOrderNoUnReserveMinersErrorException('No un reserve miners');
        }

        return $unReserveMiners;
	}

	private function isNotUnPaidCrypto(StoreOrder $unPaidOrder, \DateTime $dataTime): bool
    {
        $payment = $unPaidOrder->getPayment();
        $isNotUnPaid = false;

        if (null !== $payment) {
            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $payment->getPaymentMethod();

            $isCrypto = $paymentMethod->getName() == MakeCryptoPaymentService::PAYMENT_METHOD_NAME;

            $dataTimeUpdatedAt = $unPaidOrder->getUpdatedAt();
            $dataTimeUpdatedAt->setTimestamp($dataTimeUpdatedAt->getTimestamp() + static::RESERVE_TIME_CRYPTO);

            $isDataTimeNotOver = $dataTimeUpdatedAt > $dataTime;

            if (true === $isCrypto && true === $isDataTimeNotOver) {
                $isNotUnPaid = true;
            }
        }

        return $isNotUnPaid;
    }
}