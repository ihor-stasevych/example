<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolDeleteCommandInterface
{
    public function getMinerId(): int;

    public function getPoolId(): int;
}