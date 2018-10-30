<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\EndRentPeriodMinerStockNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class EndRentPeriodMinerStockNotificationService implements EndRentPeriodMinerStockNotificationServiceInterface
{
    private $userOrderMinerRepository;

    private $notificationService;

    public function __construct(
        UserOrderMinerRepositoryInterface $userOrderMinerRepository,
        SendUserNotificationServiceInterface $notificationService
    )
    {
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->notificationService = $notificationService;
    }

    public function execute(): void
    {
        $startDateTime = new \DateTime();
        $startDateTime->modify('+1 day');
        $h = $startDateTime->format('H');

        $endDateTime = clone $startDateTime;

        $startDateTime->setTime($h, 00, 00);
        $endDateTime->setTime($h, 59, 59);

        $userOrderMiners = $this->userOrderMinerRepository->getByBetweenEndPeriod($startDateTime, $endDateTime);

        /** @var UserOrderMiner $userOrderMiner */
        foreach ($userOrderMiners as $userOrderMiner) {
            $minersStock = $userOrderMiner->getMiners();
            $minersId = [];

            /** @var MinerStock $minerStock */
            foreach ($minersStock as $minerStock) {
                $minersId[] = $minerStock->getId();
            }

            $title = 'System notification';
            $message = 'The rental period on miners #' . implode(',', $minersId) . ' end in 1 day';

            $this->notificationService->execute($title, $message, $userOrderMiner->getUser());
        }
    }
}