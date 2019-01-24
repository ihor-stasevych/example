<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command;

interface MinerPoolCreateCommandInterface
{
    public function getMinerId(): int;

    public function getPools(): array;
}