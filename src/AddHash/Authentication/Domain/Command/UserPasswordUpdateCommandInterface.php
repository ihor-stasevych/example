<?php

namespace App\AddHash\Authentication\Domain\Command;

interface UserPasswordUpdateCommandInterface
{
    public function getCurrentPassword(): string;

    public function getNewPassword(): string;
}