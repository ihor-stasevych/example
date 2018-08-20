<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Strategy;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\UserMinerControlServiceInterface;

interface UserMinerControlStrategyInterface
{
    public function execute(UserMinerControlCommandInterface $command, UserMinerControlServiceInterface $service);
}