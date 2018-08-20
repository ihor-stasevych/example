<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolStatusUpdateCommandInterface;

interface UserMinerControlPoolStatusUpdateServiceInterface
{
    public function execute(UserMinerControlPoolStatusUpdateCommandInterface $command): array;
}