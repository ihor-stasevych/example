<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

class Miner
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

    private $stock;

	public function __construct(
	     string $title,
         string $description,
         string $hashRate,
         string $powerRate,
         string $powerEfficiency,
         string $ratedVoltage,
         string $operatingTemperature,
         MinerAlgorithm $algorithm,
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
		$this->stock = new ArrayCollection();
		$this->id = $id;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getHashRate(): string
	{
		return $this->hashRate;
	}

	public function getPowerRate(): string
	{
		return $this->powerRate;
	}

	public function getPowerEfficiency(): string
	{
		return $this->powerEfficiency;
	}

	public function getRatedVoltage(): string
	{
		return $this->ratedVoltage;
	}

	public function getOperatingTemperature(): string
	{
		return $this->operatingTemperature;
	}

	public function getAlgorithm(): MinerAlgorithm
	{
		return $this->algorithm;
	}

    public function getStock(): PersistentCollection
    {
        /** @var PersistentCollection $stock */
        $stock = $this->stock;

        return $stock;
    }
}