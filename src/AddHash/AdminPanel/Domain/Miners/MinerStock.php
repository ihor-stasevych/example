<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

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

    private $port;

	private $miner;

	private $stateAliases = [
        self::STATE_UNAVAILABLE => 'unavailable',
		self::STATE_AVAILABLE   => 'available',
		self::STATE_BUSY        => 'busy',
		self::STATE_RESERVED    => 'reserved',
	];

	public function __construct(
	    int $priority,
        string $ip,
        int $port,
        int $id = null
    )
	{
	    $this->id = $id;
		$this->state = static::STATE_DEFAULT;
        $this->priority = $priority;
		$this->ip = $ip;
		$this->port = $port;
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

	public function getPort(): int
    {
        return $this->port;
    }

    public function getMiner(): Miner
    {
        return $this->miner;
    }

	public function reserveMiner()
	{
		$this->state = self::STATE_RESERVED;
	}

	public function setAvailable()
	{
		$this->state = self::STATE_AVAILABLE;
	}
}