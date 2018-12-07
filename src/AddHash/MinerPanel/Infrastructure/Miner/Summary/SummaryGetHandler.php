<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Summary;

use App\AddHash\System\Lib\Cache\CacheInterface;
use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;
use App\AddHash\MinerPanel\Infrastructure\Miner\Extender\MinerSocket;
use App\AddHash\MinerPanel\Domain\Miner\Summary\SummaryGetHandlerInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand\MinerApiCommand;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\MinerSummaryParser;

final class SummaryGetHandler implements SummaryGetHandlerInterface
{
    private const MINER_SUMMARY_KEY = 'miner_summary_';

    private const EXPIRATION = 180;


    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function handler(Miner $miner, bool $updateCache = false): array
    {
        $key = self::MINER_SUMMARY_KEY . $miner->getId();

        if (false === $this->cache->keyExists($key) || true === $updateCache) {
            $minerApiCommand = new MinerApiCommand(
                new MinerSocket($miner),
                new MinerSummaryParser()
            );

            $summary = $minerApiCommand->getSummary();

            if (false === empty($summary)) {
                $this->cache->add($key, $summary, self::EXPIRATION);
            }
        } else {
            $summary = $this->cache->getKey($key);
        }

        return $summary;
    }
}