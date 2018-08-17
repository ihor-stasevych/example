<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMiner;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketStatusParser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoChangeStatusException;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolStatusUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolStatusUpdateServiceInterface;

class UserMinerControlPoolStatusUpdateService implements UserMinerControlPoolStatusUpdateServiceInterface
{
    private $tokenStorage;

    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param UserMinerControlPoolStatusUpdateCommandInterface $command
     * @return array
     * @throws MinerControlPoolNoChangeStatusException
     */
    public function execute(UserMinerControlPoolStatusUpdateCommandInterface $command): array
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $minerId = $command->getMinerId();
        $data = [];

        /** @var UserOrderMiner $orderMiners */
        foreach ($user->getOrderMiner() as $orderMiners) {
            /** @var MinerStock $minerStock */
            foreach ($orderMiners->getMiners() as $minerStock) {
                if ($minerStock->getId() != $minerId) {
                    continue;
                }

                $minerCommand = new MinerCommand(
                    new MinerSocket($minerStock),
                    new MinerSocketStatusParser()
                );

                $changeStatus = ($command->getStatus()) ?
                    $minerCommand->enablePool($command->getPoolId()) :
                    $changeStatus = $minerCommand->disablePool($command->getPoolId());

                if (false === $changeStatus) {
                    $this->logger->error("No change pull status", [
                        'minerId' => $minerId,
                        'poolId'  => $command->getPoolId(),
                        'status'  => $command->getStatus(),
                    ]);

                    throw new MinerControlPoolNoChangeStatusException('Did not change the status');
                }

                $minerCommand->setParser(new MinerSocketParser());

                $data = $minerCommand->getPools() + [
                    'minerTitle'   => $minerStock->infoMiner()->getTitle(),
                    'minerId'      => $minerStock->infoMiner()->getId(),
                    'minerStockId' => $minerStock->getId(),
                ];

                $this->logger->info("Change pull status", $data);

                break;
            }
        }

        return $data;
    }
}