<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolGetCommandInterface;

interface UserMinerPoolGetServiceInterface
{
    public function execute(UserMinerControlPoolGetCommandInterface $command): array;
}