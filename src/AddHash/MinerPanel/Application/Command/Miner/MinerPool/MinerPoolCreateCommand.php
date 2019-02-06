<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolCreateCommandInterface;

final class MinerPoolCreateCommand implements MinerPoolCreateCommandInterface
{
    private $minerId;

    private $pools;

    public function __construct(int $minerId, $pools)
    {
        $this->minerId = $minerId;
        $this->pools = $pools;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getPools(): array
    {
        return $this->pools;
    }
}