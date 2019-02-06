<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Command;

interface MinerPoolStatusListCommandInterface
{
    public function getMinersId(): array;
}