<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Model;

class MinerAlgorithm
{
    private $id;

    private $value;

    public function __construct(string $value, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
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