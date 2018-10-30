<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\UnReserveMinerStockServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Repository\Miner\MinerStockRepository;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Miner\UnReserveMinerStockNoMinerException;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class UnReserveMinerStockService implements UnReserveMinerStockServiceInterface
{
    private $userOrderMinerRepository;

    private $minerStockRepository;

    private $notificationService;

    public function __construct(
        UserOrderMinerRepositoryInterface $userOrderMinerRepository,
        MinerStockRepository $minerStockRepository,
        SendUserNotificationServiceInterface $notificationService
    )
    {
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->minerStockRepository = $minerStockRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws UnReserveMinerStockNoMinerException
     */
    public function execute(): void
    {
        $userOrderMiners = $this->userOrderMinerRepository->getByEndPeriod(new \DateTime());

        if (count($userOrderMiners) <= 0) {
            throw new UnReserveMinerStockNoMinerException('No miner');
        }

        /** @var UserOrderMiner $userOrderMiner */
        foreach ($userOrderMiners as $userOrderMiner) {
            $minersStock = $userOrderMiner->getMiners();
            $minersId = [];

            /** @var MinerStock $minerStock */
            foreach ($minersStock as $minerStock) {
                $minerStock->setAvailable();
                $this->minerStockRepository->save($minerStock);
                $minersId[] = $minerStock->getId();
            }

            $this->userOrderMinerRepository->remove($userOrderMiner);

            $title = 'System notification';
            $message = 'The rental period on miners #' . implode(',', $minersId) . ' is over';

            $this->notificationService->execute($title, $message, $userOrderMiner->getUser());
        }
    }
}