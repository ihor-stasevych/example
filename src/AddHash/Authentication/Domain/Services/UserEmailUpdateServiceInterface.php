<?php

namespace App\AddHash\Authentication\Domain\Services;

use App\AddHash\Authentication\Domain\Command\UserEmailUpdateCommandInterface;

interface UserEmailUpdateServiceInterface
{
    public function execute(UserEmailUpdateCommandInterface $command);
}