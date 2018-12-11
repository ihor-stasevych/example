<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm;

use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

class MinerAlgorithm
{
    private $id;

    private $value;

    private $coin;

    public function __construct(string $value, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->coin = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCoin(): PersistentCollection
    {
        /** @var PersistentCollection $coin */
        $coin = $this->coin;

        return $coin;
    }
}