<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\Lib\Mail\MailSendInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\EndRentPeriodMinerStockNotificationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class EndRentPeriodMinerStockNotificationService implements EndRentPeriodMinerStockNotificationServiceInterface
{
    private $userOrderMinerRepository;

    private $notificationService;

    private $mailSend;

    private $authenticationAdapter;

    public function __construct(
        UserOrderMinerRepositoryInterface $userOrderMinerRepository,
        SendUserNotificationServiceInterface $notificationService,
        AuthenticationAdapterInterface $authenticationAdapter,
        MailSendInterface $mailSend
    )
    {
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->notificationService = $notificationService;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->mailSend = $mailSend;
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
        $users = [];
        $usersId = [];

        /** @var UserOrderMiner $userOrderMiner */
        foreach ($userOrderMiners as $userOrderMiner) {
            $minersStock = $userOrderMiner->getMiners();
            $minersId = [];

            /** @var MinerStock $minerStock */
            foreach ($minersStock as $minerStock) {
                $minersId[] = $minerStock->getId();
            }

            $users[] = $userOrderMiner->getUser();
            $usersId[] = $userOrderMiner->getUser()->getId();

            $title = 'System notification';
            $message = 'The rental period on miners #' . implode(',', $minersId) . ' end in 1 day';

            $this->notificationService->execute($title, $message, $userOrderMiner->getUser());
        }

        $emails = $this->authenticationAdapter->getEmails($usersId);

        /** @var User $user */
        foreach ($users as $user) {
            $this->mailSend->handler(
                'emails/user-rental-period-end.html.twig',
                [
                    'name' => ''
                ],
                'The rental period end in 1 day',
                $emails[$user->getId()]
            );
        }
    }
}