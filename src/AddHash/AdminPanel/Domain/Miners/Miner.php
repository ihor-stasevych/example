<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class Miner
{
	const STATE_AVAILABLE = 1;
	const STATE_ORDER_PENDING = 2;
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

	public function __construct(
		$title, $description, $hashRate, $powerRate,
		$powerEfficiency, $ratedVoltage, $operatingTemperature,
		$ip, $algorithm, $priority
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

	public function getProduct()
	{
		//return $this->product;
	}
}