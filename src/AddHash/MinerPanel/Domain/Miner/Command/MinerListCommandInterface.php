<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Command;

interface MinerListCommandInterface
{
    public function getPage(): ?int;
}