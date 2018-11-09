<?php

namespace App\AddHash\Authentication\Domain\Services\UserData;

use App\AddHash\Authentication\Domain\Command\UserData\UserEmailsGetCommandInterface;

interface UserEmailsGetServiceInterface
{
    public function execute(UserEmailsGetCommandInterface $command): array;
}