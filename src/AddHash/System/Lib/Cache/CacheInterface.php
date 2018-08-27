<?php

namespace App\AddHash\System\Lib\Cache;


interface CacheInterface
{
	public function setPrefix($prefix = '');
	public function getPrefix();
	public function getFullPrefix();
	public function keyExists($key): bool;
	public function setKey($key, $value, $expiration = null);
	public function add($key, $value, $expiration = null);
	public function getKey($key);
	public function unsetKey($key);
	public function clear();
}