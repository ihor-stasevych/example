<?php

namespace App\AddHash\Authentication\Domain\Command;

interface UserPasswordRecoveryCommandInterface
{
    public function getHash(): string;

    public function getPassword(): string;

    public function comparePasswords(): bool;
}