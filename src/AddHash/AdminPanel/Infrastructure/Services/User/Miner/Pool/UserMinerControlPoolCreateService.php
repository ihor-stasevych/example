<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;
use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketStatusParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketCountPoolsParser;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    private const FIRST_POOL_ID = 0;

    private const SECOND_POOL_ID = 1;

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param UserMinerControlPoolCreateCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     */
    public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock)
    {
        $data = [];
        $minerId = $command->getMinerId();

        if ($minerStock->getId() != $minerId) {
            return $data;
        }

        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketCountPoolsParser()
        );

        /** Count current pools */
        $countOldPools = $minerCommand->getPools();
        $newPools = $command->getPools();

        /** Get first pool */
        $firstNewPool = array_shift($newPools);

        $minerCommand->setParser(new MinerSocketStatusParser());
        $firstNewPoolId = $countOldPools;

        /** Add first new pool */
        $firstNewPassword = !empty($firstNewPool['password']) ? $firstNewPool['password'] : ' ';
        $isAdd = $minerCommand->addPool($firstNewPool['url'], $firstNewPool['user'], ' ');
        $this->addPoolWriteLog($minerId, $firstNewPool, $isAdd);

        /** Add priority first new pool */
        $minerCommand->setPoolPriority($firstNewPoolId);

        $deleteId = static::FIRST_POOL_ID;

        /** Delete old pool */
        for ($i = 0; $i <= $countOldPools; $i++) {
            $isDelete = $minerCommand->removePool($deleteId);
            $deleteId = static::SECOND_POOL_ID;

            if (false === $isDelete) {
                $this->logger->error('No deleted pool', ['minerId' => $minerId]);
            } else {
                $this->logger->info('Pool was deleted', ['minerId' => $minerId]);
            }
        }

        /** Create the remaining new pools */
        if ($newPools) {
            $poolId = static::SECOND_POOL_ID;

            foreach ($newPools as $pool) {
                $poolPassword = !empty($pool['password']) ? $pool['password'] : '';

                $isAdd = $minerCommand->addPool($pool['url'], $pool['user'], $poolPassword);
                $this->addPoolWriteLog($minerId, $pool, $isAdd);

                if ($pool['status']) {
                    $minerCommand->enablePool($poolId);
                } else {
                    $minerCommand->disablePool($poolId);
                }

                $poolId++;
            }
        }

        /** Set status first new pool */
        if ($firstNewPool['status']) {
            $minerCommand->enablePool(static::FIRST_POOL_ID);
        } else {
            $minerCommand->disablePool(static::FIRST_POOL_ID);
        }

        $minerCommand->setParser(new MinerSocketParser());

        $data = $minerCommand->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];

        return $data;
    }

    private function addPoolWriteLog(int $minerId, array $data, bool $isAdd = false)
    {
        $logData = [
            'minerId'  => $minerId,
            'url'      => $data['url'],
            'user'     => $data['user'],
            'password' => !empty($data['password']) ? $data['password'] : '',
            'status'   => $data['status'],
        ];

        if (false === $isAdd) {
            $this->logger->error('The pool was not added', $logData);
        } else {
            $this->logger->info('Pool added', $logData);
        }
    }
}