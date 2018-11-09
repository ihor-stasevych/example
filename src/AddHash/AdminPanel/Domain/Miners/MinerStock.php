<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerStock
{
    const STATE_UNAVAILABLE = 0;

	const STATE_AVAILABLE = 1;

	const STATE_BUSY = 2;

	const STATE_RESERVED = 3;

	const STATE_DEFAULT = self::STATE_AVAILABLE;

	const DEFAULT_USER = 'root';


	private $id;

	private $state;

    private $priority;

	private $ip;

    private $port;

    private $user;

    private $configName;

    /** @var Miner */
	private $miner;

	private $product;

	private $stateAliases = [
        self::STATE_UNAVAILABLE => 'unavailable',
		self::STATE_AVAILABLE   => 'available',
		self::STATE_BUSY        => 'busy',
		self::STATE_RESERVED    => 'reserved',
	];

	public function __construct($priority, $ip, $port, $configName, $user = self::DEFAULT_USER, $id = null)
	{
	    $this->id = $id;
		$this->state = static::STATE_DEFAULT;
        $this->priority = $priority;
		$this->ip = $ip;
		$this->port = $port;
        $this->user = $user;
        $this->configName = $configName;
	}

	public function getId(): ?int
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

    public function getUser()
    {
        return $this->user;
    }

    public function getConfigName()
    {
        return $this->configName;
    }

	public function reserveMiner()
	{
		$this->state = self::STATE_RESERVED;
	}

	public function setAvailable()
	{
		$this->state = self::STATE_AVAILABLE;
	}

	public function setBusy()
	{
		$this->state = self::STATE_BUSY;
	}

	public function infoMiner()
	{
		return $this->miner;
	}
}