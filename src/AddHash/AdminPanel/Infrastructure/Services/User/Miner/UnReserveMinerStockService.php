<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\System\Lib\Mail\MailSendInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Repository\Miner\MinerStockRepository;
use App\AddHash\AdminPanel\Domain\User\Miner\UnReserveMinerStockServiceInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Miner\UnReserveMinerStockNoMinerException;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\SendUserNotificationServiceInterface;

class UnReserveMinerStockService implements UnReserveMinerStockServiceInterface
{
    private $userOrderMinerRepository;

    private $minerStockRepository;

    private $notificationService;

    private $mailSend;

    private $authenticationAdapter;

    public function __construct(
        UserOrderMinerRepositoryInterface $userOrderMinerRepository,
        MinerStockRepository $minerStockRepository,
        SendUserNotificationServiceInterface $notificationService,
        AuthenticationAdapterInterface $authenticationAdapter,
        MailSendInterface $mailSend
    )
    {
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->minerStockRepository = $minerStockRepository;
        $this->notificationService = $notificationService;
        $this->authenticationAdapter = $authenticationAdapter;
        $this->mailSend = $mailSend;
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

        $users = [];
        $usersId = [];

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

            $users[] = $userOrderMiner->getUser();
            $usersId[] = $userOrderMiner->getUser()->getId();

            $this->notificationService->execute($title, $message, $userOrderMiner->getUser());
        }

        $emails = $this->authenticationAdapter->getEmails($usersId);

        /** @var User $user */
        foreach ($users as $user) {
            $this->mailSend->handler(
                'emails/user-rental-period-is-over.html.twig',
                [
                    'name' => ''
                ],
                'The rental period is over',
                $emails[$user->getId()]
            );
        }
    }
}