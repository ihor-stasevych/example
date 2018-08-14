<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

class MinerDetails
{
	private $id;

	private $title;

	private $description;

	private $hashRate;

	private $powerRate;

	private $powerEfficiency;

	private $ratedVoltage;

	private $operatingTemperature;

	private $algorithm;

	private $miner;

	public function __construct(
	    $title,
        $description,
        $hashRate,
        $powerRate,
        $powerEfficiency,
        $ratedVoltage,
        $operatingTemperature,
        $algorithm,
        $id = null
    )
	{
		$this->title = $title;
		$this->description = $description;
		$this->hashRate = $hashRate;
		$this->powerRate = $powerRate;
		$this->powerEfficiency = $powerEfficiency;
		$this->ratedVoltage = $ratedVoltage;
		$this->operatingTemperature = $operatingTemperature;
		$this->algorithm = $algorithm;
		$this->id = $id;
	}

    public function getId()
    {
        return $this->id;
    }

	public function getTitle()
	{
		return $this->title;
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

	public function setMiner(Miner $miner)
    {
        $this->miner = $miner;
    }
}