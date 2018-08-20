<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolDeleteCommandInterface;

interface UserMinerControlPoolDeleteServiceInterface
{
    public function execute(UserMinerControlPoolDeleteCommandInterface $command);
}