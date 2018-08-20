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
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoAddedException;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketCountPoolsParser;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlMaxCountPoolsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    const MAX_COUNT_POOLS = 3;

    private $tokenStorage;

    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @param UserMinerControlPoolCreateCommandInterface $command
     * @return array|null
     * @throws MinerControlMaxCountPoolsException
     * @throws MinerControlPoolNoAddedException
     */
    public function execute(UserMinerControlPoolCreateCommandInterface $command)
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
                    new MinerSocketCountPoolsParser()
                );

                if ($minerCommand->getPools() >= static::MAX_COUNT_POOLS) {
                    $this->logger->error('The pool limit is exceeded', [
                        'minerId' => $minerId,
                    ]);

                    throw new MinerControlMaxCountPoolsException('The pool limit is exceeded');
                }

                $minerCommand->setParser(new MinerSocketStatusParser());
                $isAddPool = $minerCommand->addPool($command->getUrl(), $command->getUser(), $command->getPassword());

                if (false === $isAddPool) {
                    $this->logger->error('The pool was not added', [
                        'minerId'  => $minerId,
                        'url'      => $command->getUrl(),
                        'user'     => $command->getUser(),
                        'password' => $command->getPassword(),
                    ]);

                    throw new MinerControlPoolNoAddedException('The pool was not added');
                }

                $minerCommand->setParser(new MinerSocketParser());

                $data = $minerCommand->getPools() + [
                    'minerTitle'   => $minerStock->infoMiner()->getTitle(),
                    'minerId'      => $minerStock->infoMiner()->getId(),
                    'minerStockId' => $minerStock->getId(),
                ];

                $this->logger->info('Pool added', $data);

                break;
            }
        }

        return $data;
    }
}