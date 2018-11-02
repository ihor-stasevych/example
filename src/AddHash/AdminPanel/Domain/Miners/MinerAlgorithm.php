<?php

namespace App\AddHash\AdminPanel\Domain\Miners;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

class MinerAlgorithm
{
	private $id;

	private $value;

	private $allowedUrl;

	private $miner;

	public function __construct(string $value, int $id = null)
	{
	    $this->value = $value;
		$this->id = $id;
        $this->miner = new ArrayCollection();
        $this->allowedUrl = new ArrayCollection();
	}

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getValue(): string
    {
        return $this->value;
    }

    public function getAllowedUrl(): PersistentCollection
    {
        /** @var PersistentCollection $allowedUrl */
        $allowedUrl = $this->allowedUrl;

        return $allowedUrl;
    }
}
