<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Domain\Miners\Commands\MinerCommandInterface;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketStatusParser;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlPoolNoAddedException;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketCountPoolsParser;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    private const FIRST_POOL_ID = 0;

    private const SECOND_POOL_ID = 1;

    private $logger;

    /**
     * @var MinerCommandInterface
     */
    private $minerCommand;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param UserMinerControlPoolCreateCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     * @throws MinerControlPoolNoAddedException
     */
    public function execute(UserMinerControlPoolCreateCommandInterface $command, MinerStock $minerStock)
    {
        $data = [];
        $minerId = $command->getMinerId();

        if ($minerStock->getId() != $minerId) {
            return $data;
        }

        $this->minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketCountPoolsParser()
        );

        /** Count current pools */
        $countOldPools = $this->minerCommand->getPools();
        $newPools = $command->getPools();

        /** Get first pool */
        $firstNewPool = array_shift($newPools);

        $this->minerCommand->setParser(new MinerSocketStatusParser());
        $firstNewPoolId = $countOldPools;

        /** Add first new pool */
        $this->addPool($minerId, $firstNewPool);

        /** Add priority first new pool */
        $this->minerCommand->setPoolPriority($firstNewPoolId);

        $counterDelete = 0;

        /** Delete old pool */
        for ($i = 0; $i <= $countOldPools; $i++) {
            /**
             * After the first deletion, the priority pool becomes index 0
             * then delete from index 1
             */
            $deleteId = ($counterDelete < 1) ? static::FIRST_POOL_ID : static::SECOND_POOL_ID;

            $isDelete = $this->minerCommand->removePool($deleteId);

            if (false === $isDelete) {
                $this->logger->error('No deleted pool', ['minerId' => $minerId]);
            } else {
                $this->logger->info('Pool was deleted', ['minerId' => $minerId]);
            }

            $counterDelete++;
        }

        /** Create the remaining new pools */
        if ($newPools) {
            $poolId = static::SECOND_POOL_ID;

            foreach ($newPools as $pool) {
                $this->addPool($minerId, $pool);
                $this->setStatusPool($pool['status'], $minerId);
                $poolId++;
            }
        }

        $this->setStatusPool($firstNewPool['status'], static::FIRST_POOL_ID);

        $this->minerCommand->setParser(new MinerSocketParser());

        $data = $this->minerCommand->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];

        return $data;
    }

    /**
     * @param int $minerId
     * @param array $data
     * @throws MinerControlPoolNoAddedException
     */
    private function addPool(int $minerId, array $data)
    {
        $isAddPool = $this->minerCommand->addPool($data['url'], $data['user'], $data['password']);

        if (false === $isAddPool) {
            $this->logger->error('The pool was not added', [
                'minerId'  => $minerId,
                'url'      => $data['url'],
                'user'     => $data['user'],
                'password' => $data['password'],
            ]);

            throw new MinerControlPoolNoAddedException('The pool was not added');
        } else {
            $this->logger->info('Pool added', [
                'minerId'  => $minerId,
                'url'      => $data['url'],
                'user'     => $data['user'],
                'password' => $data['password'],
            ]);
        }
    }

    /**
     * @param int $status
     * @param int $poolId
     */
    private function setStatusPool(int $status, int $poolId)
    {
        if ($status) {
            $this->minerCommand->enablePool($poolId);
        } else {
            $this->minerCommand->disablePool($poolId);
        }
    }
}