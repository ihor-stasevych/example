<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\UnReserveMinerStockServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Repository\Miner\MinerStockRepository;

class UnReserveMinerStockService implements UnReserveMinerStockServiceInterface
{
    private $userOrderMinerRepository;

    private $minerStockRepository;

    public function __construct(UserOrderMinerRepositoryInterface $userOrderMinerRepository, MinerStockRepository $minerStockRepository)
    {
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->minerStockRepository = $minerStockRepository;
    }

    public function execute(): void
    {
        $userOrderMiners = $this->userOrderMinerRepository->getByEndPeriod(new \DateTime());

        if (count($userOrderMiners) <= 0) {

        }

        /** @var UserOrderMiner $userOrderMiner */
        foreach ($userOrderMiners as $userOrderMiner) {
            $minersStock = $userOrderMiner->getMiners();

            /** @var MinerStock $minerStock */
            foreach ($minersStock as $minerStock) {
                $minerStock->setBusy();
                $this->minerStockRepository->save($minerStock);
            }

            $this->userOrderMinerRepository->remove($userOrderMiner);
        }
    }
}