<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCommandInterface;

interface UserMinerPoolGetServiceInterface
{
    public function execute(UserMinerControlPoolCommandInterface $command): array;
}