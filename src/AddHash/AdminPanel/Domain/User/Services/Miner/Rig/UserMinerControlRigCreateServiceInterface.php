<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Rig;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigCreateCommandInterface;

interface UserMinerControlRigCreateServiceInterface
{
    public function execute(UserMinerControlRigCreateCommandInterface $command): array;
}