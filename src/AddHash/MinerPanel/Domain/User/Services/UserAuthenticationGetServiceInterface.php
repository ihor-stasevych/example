<?php

namespace App\AddHash\MinerPanel\Domain\User\Services;

use App\AddHash\MinerPanel\Domain\User\Model\User;

interface UserAuthenticationGetServiceInterface
{
    public function execute(): User;
}