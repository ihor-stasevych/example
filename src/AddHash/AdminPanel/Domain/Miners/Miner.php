<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProduct;

class Miner
{
    const STATE_UNAVAILABLE = 0;

	const STATE_AVAILABLE = 1;

	const STATE_BUSY = 2;

	const STATE_RESERVED = 3;

	const STATE_DEFAULT = self::STATE_AVAILABLE;


	private $id;

	private $state;

    private $priority;

	private $ip;

    private $port;

	private $product;

	private $details;

	private $stateAliases = [
        self::STATE_UNAVAILABLE => 'unavailable',
		self::STATE_AVAILABLE   => 'available',
		self::STATE_BUSY        => 'busy',
		self::STATE_RESERVED    => 'reserved',
	];

	public function __construct($priority, $ip, $port, $id = null)
	{
	    $this->id = $id;
		$this->state = static::STATE_DEFAULT;
        $this->priority = $priority;
		$this->ip = $ip;
		$this->port = $port;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getState()
	{
		return $this->state;
	}

    public function getPriority()
    {
        return $this->priority;
    }

	public function getStateAlias()
	{
		return $this->stateAliases[$this->state];
	}

    public function getIp()
    {
        return $this->ip;
    }

	public function getPort()
    {
        return $this->port;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getProduct()
    {
        return $this->product;
    }

	public function reserveMiner()
	{
		$this->state = self::STATE_RESERVED;
	}

	public function setAvailable()
	{
		$this->state = self::STATE_AVAILABLE;
	}

	public function setProduct(StoreProduct $product)
    {
        $this->product = $product;
    }
}