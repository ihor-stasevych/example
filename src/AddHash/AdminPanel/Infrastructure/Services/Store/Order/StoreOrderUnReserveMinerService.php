<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrder;
use App\AddHash\AdminPanel\Domain\Store\Order\Item\StoreOrderItem;
use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnPaidErrorException;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderUnReserveMinerServiceInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnReserveMinersErrorException;

class StoreOrderUnReserveMinerService implements StoreOrderUnReserveMinerServiceInterface
{
    private const RESERVE_TIME = 900;

    private $storeOrderRepository;

    private $minerStockRepository;

	public function __construct(StoreOrderRepositoryInterface $storeOrderRepository, MinerStockRepositoryInterface $minerStockRepository)
	{
        $this->storeOrderRepository = $storeOrderRepository;
        $this->minerStockRepository = $minerStockRepository;
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

            /** @var StoreOrderItem $item **/
            foreach ($items as $item) {
                $minerStock = $item->getProduct()->unReserveMiner();

                if ($minerStock) {
                    $unReserveMiners[] = $minerStock;
                    $this->minerStockRepository->save($minerStock);
                }
            }
        }

        if (!$unReserveMiners) {
            throw new StoreOrderNoUnReserveMinersErrorException('No un reserve miners');
        }

        return $unReserveMiners;
	}
}