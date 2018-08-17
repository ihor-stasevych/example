<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

interface UserMinerControlPoolCreateServiceInterface
{
    public function execute(UserMinerControlPoolCreateCommandInterface $command);
}