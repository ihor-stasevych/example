<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerHashRate;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

class MinerHashRate
{
    private $id;

    private $value;

    private $createdAt;

    private $miner;

    public function __construct(float $value, Miner $miner, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->createdAt = new \DateTime();
        $this->miner = $miner;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getMiner(): Miner
    {
        return $this->miner;
    }
}