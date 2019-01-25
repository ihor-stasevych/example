<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerHashRate;

use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;

class MinerHashRate
{
    private $id;

    private $value;

    private $createdAt;

    private $algorithm;

    private $user;

    public function __construct(float $value, MinerAlgorithm $algorithm, User $user, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->createdAt = new \DateTime();
        $this->algorithm = $algorithm;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getAlgorithm(): MinerAlgorithm
    {
        return $this->algorithm;
    }
}