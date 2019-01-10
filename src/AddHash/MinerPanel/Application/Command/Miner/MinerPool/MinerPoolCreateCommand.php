<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerPool;

final class MinerPoolCreateCommand
{
    private $id;

    private $pools;

    public function __construct(int $id, $pools)
    {
        $this->id = $id;
        $this->pools = $pools;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPools(): array
    {
        return $this->pools;
    }
}