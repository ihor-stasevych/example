<?php

namespace App\AddHash\System\Lib\Cache;

use Psr\Cache\CacheItemPoolInterface;

class RedisCache implements CacheInterface
{
	/**
	 * @var CacheItemPoolInterface
	 */
	protected $adapter;

	/**
	 * @var string
	 */
	protected $env = '';

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * @var string
	 */
	protected $fullPrefix = '';

	/**
	 * RedisCache constructor.
	 * @param CacheItemPoolInterface $adapter
	 * @param string $env
	 */
	public function __construct(CacheItemPoolInterface $adapter, $env = '')
	{
		$this->adapter = $adapter;
		$this->env = $env;
		$this->fullPrefix = $this->env;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix = '')
	{
		$this->prefix = $prefix;
		$this->fullPrefix = $this->env . '-' . $prefix;
	}

	/**
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * @return string
	 */
	public function getFullPrefix()
	{
		return $this->fullPrefix;
	}

	/**
	 * @param $key
	 * @return bool
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function keyExists($key): bool
	{
		return $this->adapter->getItem($this->getKeyWithPrefix($key))->isHit();
	}

	/**
	 * @param $key
	 * @param $value
	 * @param null $expiration
	 * @return bool
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function setKey($key, $value, $expiration = null)
	{
		$item = $this->adapter->getItem($this->getKeyWithPrefix($key));
		$item->set($value);

		if ($expiration > 0) {
			$item->expiresAfter($expiration);
		}

		return $this->adapter->save($item);
	}

	/**
	 * @param $key
	 * @param $value
	 * @param null $expiration
	 * @return bool
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function add($key, $value, $expiration = null)
	{
		$item = $this->adapter->getItem($this->getKeyWithPrefix($key));
		$item->set($value);

		if ($expiration > 0) {
			$item->expiresAfter($expiration);
		}

		return $this->adapter->save($item);
	}

	/**
	 * @param $key
	 * @return bool|mixed
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function getKey($key)
	{
		return $this->adapter->getItem($this->getKeyWithPrefix($key))->get();
	}

	/**
	 * @param $key
	 * @return bool
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function unsetKey($key)
	{
		return $this->adapter->deleteItem($this->getKeyWithPrefix($key));
	}

	/**
	 * @return bool
	 */
	public function clear()
	{
		return $this->adapter->clear();
	}

	/**
	 * @param $key
	 * @return string
	 */
	private function getKeyWithPrefix($key)
	{
		return $this->getFullPrefix() . '-' . $key;
	}
}
