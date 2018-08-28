<?php

namespace App\AddHash\AdminPanel\Infrastructure\Repository\User\Miner;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Miners\Parsers\ParserInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\UserMinerRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\System\Lib\Cache\CacheInterface;

class UserMinerRepository implements UserMinerRepositoryInterface
{
	const MINER_SUMMARY_KEY = 'minerSummary';

	private $cache;
	private $parser;

	public function __construct(CacheInterface $cache)
	{
		$this->cache = $cache;
	}

	public function getSummary(MinerStock $minerStock)
	{
		$key = self::MINER_SUMMARY_KEY . '_' . $minerStock->getId();

		if ($this->cache->keyExists($key)) {
			return $this->cache->getKey($key);
		}

		$command = new MinerCommand(new MinerSocket($minerStock), new MinerSocketParser());

		$result = $command->getSummary() + [
				'minerTitle'   => $minerStock->infoMiner()->getTitle(),
				'minerId'      => $minerStock->infoMiner()->getId(),
				'minerStockId' => $minerStock->getId()
		];

		$this->cache->add($key, $result);

		return $result;
	}
}