<?php

namespace App\AddHash\Authentication\Domain\Command\UserData;

interface UserEmailsGetCommandInterface
{
    public function getUsersId(): array;
}