<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Rig;

use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigGetCommandInterface;

final class UserMinerControlRigGetCommand implements UserMinerControlRigGetCommandInterface
{
    private $rigId;

    public function __construct($rigId)
    {
        $this->rigId = $rigId;
    }

    public function getRigId(): int
    {
        return $this->rigId;
    }
}