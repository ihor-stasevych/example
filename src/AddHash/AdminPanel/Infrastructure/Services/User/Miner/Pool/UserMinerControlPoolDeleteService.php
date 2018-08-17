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
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoDeleteException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolDeleteCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolDeleteServiceInterface;

class UserMinerControlPoolDeleteService implements UserMinerControlPoolDeleteServiceInterface
{
    private $tokenStorage;

    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param UserMinerControlPoolDeleteCommandInterface $command
     * @return array|null
     * @throws MinerControlPoolNoDeleteException
     */
    public function execute(UserMinerControlPoolDeleteCommandInterface $command)
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

                $isDelete = $minerCommand->removePool($command->getPoolId());

                if (false === $isDelete) {
                    $this->logger->error('No deleted pool', [
                        'minerId' => $minerId,
                        'poolId'  => $command->getPoolId(),
                    ]);

                    throw new MinerControlPoolNoDeleteException('The pool was not deleted');
                }

                $parser = new MinerSocketParser();
                $minerCommand->setParser($parser);

                $data = $minerCommand->getPools() + [
                    'minerTitle'   => $minerStock->infoMiner()->getTitle(),
                    'minerId'      => $minerStock->infoMiner()->getId(),
                    'minerStockId' => $minerStock->getId(),
                ];

                $this->logger->info('Pool was deleted (id = ' . $command->getPoolId() . ')', $data);

                break;
            }
        }

        return $data;
    }
}