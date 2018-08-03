<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class Miner
{
	const STATE_AVAILABLE = 1;
	const STATE_BUSY = 2;
	const STATE_RESERVED = 3;

	const STATE_UNAVAILABLE = 0;

	private $id;

	private $title;

	private $description;

	private $hashRate;

	private $powerRate;

	private $powerEfficiency;

	private $ratedVoltage;

	private $operatingTemperature;

	private $state;

	private $ip;

	private $algorithm;

	private $priority;

	private $product;

	private $port;

	private $stateAliases = [
		self::STATE_AVAILABLE => 'available',
		self::STATE_BUSY => 'busy',
		self::STATE_RESERVED => 'reserved',
		self::STATE_UNAVAILABLE => 'unavailable'
	];

	public function __construct(
		$title, $description, $hashRate, $powerRate,
		$powerEfficiency, $ratedVoltage, $operatingTemperature,
		$ip, $algorithm, $priority, $port
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->hashRate = $hashRate;
		$this->powerRate = $powerRate;
		$this->powerEfficiency = $powerEfficiency;
		$this->ratedVoltage = $ratedVoltage;
		$this->operatingTemperature = $operatingTemperature;
		$this->state = self::STATE_AVAILABLE;
		$this->algorithm = $algorithm;
		$this->ip = $ip;
		$this->priority = $priority;
		$this->port = $port;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getState()
	{
		return $this->state;
	}

	public function getStateAlias()
	{
		return $this->stateAliases[$this->state];
	}

	public function getHashRate()
	{
		return $this->hashRate;
	}

	public function getPowerRate()
	{
		return $this->powerRate;
	}

	public function getPowerEfficiency()
	{
		return $this->powerEfficiency;
	}

	public function getRatedVoltage()
	{
		return $this->ratedVoltage;
	}

	public function getOperatingTemperature()
	{
		return $this->operatingTemperature;
	}

	public function getAlgorithm()
	{
		return $this->algorithm;
	}

    public function getIp()
    {
        return $this->ip;
    }

	public function getPort()
    {
        return $this->port;
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