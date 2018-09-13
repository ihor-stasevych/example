<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolCreateCommandInterface extends UserMinerControlPoolCommandInterface
{
    public function getPools(): array;
}