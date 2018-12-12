<?php

namespace App\AddHash\MinerPanel\Domain\Package\Options\Model;


class PackageOptions
{
	private $id;

	private $maxAllowedMiners;

	private $package;

	/**
	 * PackageOptions constructor.
	 *
	 * @param int $id
	 * @param int $maxAllowedMiners
	 */
	public function __construct(int $id, int $maxAllowedMiners)
	{
		$this->id = $id;
		$this->maxAllowedMiners = $maxAllowedMiners;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getMaxAllowedMiners(): int
	{
		return $this->maxAllowedMiners;
	}
}