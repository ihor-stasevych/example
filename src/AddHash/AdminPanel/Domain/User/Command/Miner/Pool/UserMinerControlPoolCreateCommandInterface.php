<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolCreateCommandInterface
{
    public function getMinerId(): int;

    public function getPools(): array;
}