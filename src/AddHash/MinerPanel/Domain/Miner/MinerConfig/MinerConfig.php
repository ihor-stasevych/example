<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerConfig;

class MinerConfig
{
    private $id;

    private $name;

    private $miner;

    public function __construct(string $name, int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}