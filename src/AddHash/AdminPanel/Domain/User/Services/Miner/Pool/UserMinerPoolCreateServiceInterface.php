<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

interface UserMinerPoolCreateServiceInterface
{
    public function execute(UserMinerControlPoolCreateCommandInterface $command): void;
}