<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolStatusUpdateCommandInterface
{
    public function getMinerId(): int;

    public function getPoolId(): int;

    public function getStatus(): int;
}