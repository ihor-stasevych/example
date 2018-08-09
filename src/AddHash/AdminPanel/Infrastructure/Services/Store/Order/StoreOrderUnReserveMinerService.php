<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Services\StoreOrderUnReserveMinerServiceInterface;

class StoreOrderUnReserveMinerService implements StoreOrderUnReserveMinerServiceInterface
{
    private const RESERVE_TIME = 900;

    private $storeOrderRepository;

    private $minerRepository;

	public function __construct(StoreOrderRepositoryInterface $storeOrderRepository, MinerRepositoryInterface $minerRepository)
	{
        $this->storeOrderRepository = $storeOrderRepository;
        $this->minerRepository = $minerRepository;
	}

	public function execute()
	{
        $dataTime = new \DateTime();
        $dataTime->setTimestamp(time() - static::RESERVE_TIME);
	    $unpaidOrders = $this->storeOrderRepository->getNewByTime($dataTime);

	    if ($unpaidOrders) {

            foreach ($unpaidOrders as $unpaidOrder) {
                $items = $unpaidOrder->getItems();

                foreach ($items as $item) {
                    $miner = $item->getProduct()->unReserveMiner();

                    if ($miner) {
                        $this->minerRepository->save($miner);
                    }
                }
            }
        }
	}
}