<?php

namespace App\AddHash\AdminPanel\Infrastructure\Info\Services;

use App\AddHash\AdminPanel\Domain\Info\Services\GetCryptoCurrenciesServiceInterface;
use App\AddHash\System\Lib\Cache\CacheInterface;

class GetCryptoCurrenciesService implements GetCryptoCurrenciesServiceInterface
{
	const URL = 'http://api.coinmarketcap.com/v2/ticker/';
	const CACHE_KEY = 'info.cryptoCurrencies';

	private $cache;

	public function __construct(CacheInterface $cache)
	{
		$this->cache = $cache;
	}

	public function execute()
	{
		if ($this->cache->keyExists(self::CACHE_KEY)) {
			return $this->cache->getKey(self::CACHE_KEY);
		}

		if (!$response = file_get_contents(self::URL)) {
			return null;
		}

		$response = json_decode($response, true);
		$this->cache->add(self::CACHE_KEY, $response);

		return $response;
	}
}