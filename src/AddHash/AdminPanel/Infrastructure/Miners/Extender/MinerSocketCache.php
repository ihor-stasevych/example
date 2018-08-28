<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Extender;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\System\Lib\Cache\CacheInterface;

class MinerSocketCache extends MinerSocket
{
	private $cache;

	public function __construct(MinerStock $minerStock, CacheInterface $cache)
	{
		$this->cache = $cache;
		parent::__construct($minerStock);
	}


	public function request(string $cmd): string
	{

		return parent::request($cmd);
	}
}