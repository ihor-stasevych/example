<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Model\UserPasswordRecovery;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryHashCommandInterface;

interface UserCheckRecoveryHashServiceInterface
{
    public function execute(UserPasswordRecoveryHashCommandInterface $command): UserPasswordRecovery;
}