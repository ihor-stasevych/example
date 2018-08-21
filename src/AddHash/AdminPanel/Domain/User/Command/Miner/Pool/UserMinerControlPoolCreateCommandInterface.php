<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;

interface UserMinerControlPoolCreateCommandInterface extends UserMinerControlCommandInterface
{
    public function getMinerId(): int;

    public function getPools(): array;
}