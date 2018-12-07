<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerInfo;

use App\AddHash\System\Lib\Cache\CacheInterface;
use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\Parser;
use App\AddHash\MinerPanel\Infrastructure\Miner\Extender\MinerSocket;
use App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand\MinerApiCommand;
use App\AddHash\MinerPanel\Domain\Miner\ApiCommand\AbstractMinerApiCommandInterface;

abstract class AbstractMinerInfoHandler
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function handler(Miner $miner, bool $updateCache = false): array
    {
        $key = static::MINER_INFO_KEY . $miner->getId();

        if (false === $this->cache->keyExists($key) || true === $updateCache) {

            $minerApiCommand = new MinerApiCommand(
                new MinerSocket($miner),
                $this->getParser()
            );

            $result = $this->executeCommand($minerApiCommand);

            if (false === empty($summary)) {
                $this->cache->add($key, $result, static::EXPIRATION);
            }
        } else {
            $result = $this->cache->getKey($key);
        }

        return $result;
    }

    abstract protected function getParser(): Parser;

    abstract protected function executeCommand(AbstractMinerApiCommandInterface $command): array;
}