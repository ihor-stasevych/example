<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command;

interface MinerPoolCreateCommandInterface
{
    public function getId(): int;

    public function getPools(): array;
}