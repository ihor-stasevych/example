<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Extender;

interface MinerSocketInterface
{
    public function request(string $cmd): string;
}