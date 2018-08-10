<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Order;

use App\AddHash\AdminPanel\Domain\Store\Order\StoreOrderRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnPaidErrorException;
use App\AddHash\AdminPanel\Domain\Store\Order\Exceptions\StoreOrderNoUnReserveMinersErrorException;
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

        foreach ($unPaidOrders as $unPaidOrder) {
            $items = $unPaidOrder->getItems();

            foreach ($items as $item) {
                $miner = $item->getProduct()->unReserveMiner();

                if ($miner) {
                    $unReserveMiners[] = $miner;
                    $this->minerRepository->save($miner);
                }
            }
        }

        if (!$unReserveMiners) {
            throw new StoreOrderNoUnReserveMinersErrorException('No un reserve miners');
        }

        return $unReserveMiners;
	}
}