<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Domain\Miners\Commands\MinerCommandInterface;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketStatusParser;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketCountPoolsParser;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Miner\Pool\UserMinerNoValidUrlPoolException;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    private const FIRST_POOL_ID = 0;

    private const SECOND_POOL_ID = 1;

    private const DELAY_REPEAT_DELETE_FIRST_POOL = 1;

    private const MAX_COUNT_REPEAT_DELETE_FIRST_POOL = 30;


    private $logger;

    private $allowedUrlRepository;

    private $countRepeatDeleteFirstPool = 0;

    public function __construct(LoggerInterface $logger, MinerAllowedUrlRepositoryInterface $allowedUrlRepository)
    {
        $this->logger = $logger;
        $this->allowedUrlRepository = $allowedUrlRepository;
    }

    /**
     * @param UserMinerControlPoolCreateCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     * @throws UserMinerNoValidUrlPoolException
     */
    public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock)
    {
        $data = [];
        $minerId = $command->getMinerId();

        if ($minerStock->getId() != $minerId) {
            return $data;
        }

        $getCountAllowedUrl = $this->allowedUrlRepository->getCountByValuesEnabledUrl($command->getUniqueUrls());

        if ($getCountAllowedUrl != count($command->getUniqueUrls())) {
            throw new UserMinerNoValidUrlPoolException('No valid url');
        }

        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketCountPoolsParser()
        );

        /** Count current pools */
        $countOldPools = $minerCommand->getPools();

        $this->logger->info('Get count pools = ' . $countOldPools, [
            'minerStockId' => $minerId,
        ]);

        $minerCommand->setParser(new MinerSocketStatusParser());

        $newPools = $command->getPools();

        /** Add first new pool */
        $firstNewPool = array_shift($newPools);

        $pool = $minerCommand->addPool(
            $firstNewPool['url'],
            $firstNewPool['user'],
            $this->normalizePassword($firstNewPool['password'])
        );

        $loggerData = [
            'minerStockId' => $minerId,
            'url'          => $firstNewPool['url'],
            'user'         => $firstNewPool['user'],
            'password'     => $firstNewPool['password'],
            'data'         => $pool['data'],
        ];

        if ($pool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
            $this->logger->info('The pool has been added', $loggerData);
        } else {
            $this->logger->error('The pool was not added', $loggerData);
        }

        $firstNewPoolId = $countOldPools;

        /** Add priority first new pool */
        $priority = $minerCommand->setPoolPriority($firstNewPoolId);

        $loggerData['data'] = $priority['data'];

        if ($priority['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
            $this->logger->info('Priority is set', $loggerData);
        } else {
            $this->logger->error('Priority not set', $loggerData);
        }

        /** Delete old pool */
        $loggerData = [
            'minerStockId' => $minerId,
        ];

        /** Trying to remove the first pool */
        $this->deleteFirstPool($minerCommand, $minerId);

        for ($i = static::SECOND_POOL_ID; $i < $countOldPools; $i++) {
            $pool = $minerCommand->removePool(static::SECOND_POOL_ID);

            $loggerData['data'] = $pool['data'];

            if ($pool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
                $this->logger->info('The pool has been deleted', $loggerData);
            } else {
                $this->logger->error('The pool was not deleted', $loggerData);
            }
        }

        if ($newPools) {
            $poolId = static::SECOND_POOL_ID;

            foreach ($newPools as $pool) {
                $addPool = $minerCommand->addPool(
                    $pool['url'],
                    $pool['user'],
                    $this->normalizePassword($pool['password'])
                );

                $loggerData = [
                    'minerStockId' => $minerId,
                    'url'          => $pool['url'],
                    'user'         => $pool['user'],
                    'password'     => $pool['password'],
                    'data'         => $addPool['data'],
                ];

                if ($addPool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
                    $this->logger->info('The pool has been added', $loggerData);

                    $loggerData['status'] = $pool['status'];

                    $this->changeStatus($minerCommand, $pool['status'], $poolId, $loggerData);
                } else {
                    $this->logger->error('The pool was not added', $loggerData);
                }

                $poolId++;
            }
        }

        $loggerData = [
            'minerStockId' => $minerId,
            'url'          => $firstNewPool['url'],
            'user'         => $firstNewPool['user'],
            'password'     => $firstNewPool['password'],
            'status'       => $firstNewPool['status']
        ];

        $this->changeStatus($minerCommand, $firstNewPool['status'], static::FIRST_POOL_ID, $loggerData);

        $minerCommand->setParser(new MinerSocketParser());

        return $minerCommand->getPools() + [
            'minerTitle'   => $minerStock->infoMiner()->getTitle(),
            'minerId'      => $minerStock->infoMiner()->getId(),
            'minerStockId' => $minerStock->getId(),
        ];
    }

    private function changeStatus(MinerCommandInterface $minerCommand, int $status, int $poolId, array $loggerData)
    {
        if ($status) {
            $enablePool = $minerCommand->enablePool($poolId);

            if ($enablePool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
                $this->logger->info('Enable status is set', $loggerData);
            } else {
                $this->logger->info('Enable status is not set', $loggerData);
            }
        } else {
            $disablePool = $minerCommand->disablePool($poolId);

            if ($disablePool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
                $this->logger->info('Disable status is set', $loggerData);
            } else {
                $this->logger->info('Disable status is not set', $loggerData);
            }
        }
    }

    private function normalizePassword($password)
    {
        return !empty($password) ? $password : ' ';
    }

    private function deleteFirstPool(MinerCommandInterface $minerCommand, int $minerStockId)
    {
        $pool = $minerCommand->removePool(static::FIRST_POOL_ID);
        $loggerData = [
            'minerStockId' => $minerStockId,
            'data'         => $pool['data'],
        ];

        if ($pool['status'] == MinerSocketStatusParser::STATUS_SUCCESS) {
            $this->logger->info('Remove the first pool', $loggerData);
        } else {

            if ($this->countRepeatDeleteFirstPool < static::MAX_COUNT_REPEAT_DELETE_FIRST_POOL) {
                $this->countRepeatDeleteFirstPool++;
                $this->logger->info('Trying to remove the first pool', $loggerData);

                sleep(static::DELAY_REPEAT_DELETE_FIRST_POOL);
                $this->deleteFirstPool($minerCommand, $minerStockId);
            } else {
                $this->logger->info('Could not delete the first pool', $loggerData);
            }
        }
    }
}