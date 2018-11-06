<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

class MinerStock
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

    private $config;

    private $miner;

    private $pool;

    private $product;

	private $stateAliases = [
        self::STATE_UNAVAILABLE => 'unavailable',
		self::STATE_AVAILABLE   => 'available',
		self::STATE_BUSY        => 'busy',
		self::STATE_RESERVED    => 'reserved',
	];

	public function __construct(int $priority, string $ip, MinerStockConfig $config, int $id = null)
	{
	    $this->id = $id;
		$this->state = static::STATE_DEFAULT;
        $this->priority = $priority;
		$this->ip = $ip;
        $this->config = $config;
        $this->pool = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getState(): int
	{
		return $this->state;
	}

    public function getPriority(): int
    {
        return $this->priority;
    }

	public function getStateAlias(): string
	{
		return $this->stateAliases[$this->state];
	}

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getConfig(): MinerStockConfig
    {
        return $this->config;
    }

    public function getPool(): PersistentCollection
    {
        /** @var PersistentCollection $pool */
        $pool = $this->pool;

        return $pool;
    }

    public function infoMiner(): Miner
    {
        return $this->miner;
    }

	public function reserveMiner(): void
	{
		$this->state = self::STATE_RESERVED;
	}

	public function setAvailable(): void
	{
		$this->state = self::STATE_AVAILABLE;
	}

	public function setBusy(): void
	{
		$this->state = self::STATE_BUSY;
	}
}