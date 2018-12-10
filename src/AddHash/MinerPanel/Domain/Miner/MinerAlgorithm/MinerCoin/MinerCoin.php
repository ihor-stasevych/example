<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;

class MinerCoin
{
    private $id;

    private $value;

    private $algorithm;

    public function __construct(string $value, MinerAlgorithm $algorithm, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->algorithm = $algorithm;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}