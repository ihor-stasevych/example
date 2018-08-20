<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolUpdateCommandInterface;

interface UserMinerControlPoolUpdateServiceInterface
{
    public function execute(UserMinerControlPoolUpdateCommandInterface $command);
}