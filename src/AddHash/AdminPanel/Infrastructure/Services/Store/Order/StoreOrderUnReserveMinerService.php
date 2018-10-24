<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnPaidErrorException;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderUnReserveMinerServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnReserveMinersErrorException;

class StoreOrderUnReserveMinerService implements StoreOrderUnReserveMinerServiceInterface
{
    private const RESERVE_TIME = 900;

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
        $dataTime->setTimestamp($dataTime->getTimestamp() - static::RESERVE_TIME);
	    $unPaidOrders = $this->storeOrderRepository->getNewByTime($dataTime);

	    if (!$unPaidOrders) {
            throw new StoreOrderNoUnPaidErrorException('No un paid orders');
        }

        $unReserveMiners = [];

	    /** @var  StoreOrder $unPaidOrder */
        foreach ($unPaidOrders as $unPaidOrder) {
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
}