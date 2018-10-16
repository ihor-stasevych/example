<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Command\UserPasswordUpdateCommandInterface;

interface UserPasswordUpdateServiceInterface
{
    public function execute(UserPasswordUpdateCommandInterface $command);
}