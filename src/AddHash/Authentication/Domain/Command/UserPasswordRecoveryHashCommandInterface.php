<?php

namespace App\AddHash\Authentication\Domain\Command;

interface UserPasswordRecoveryHashCommandInterface
{
    public function getHash(): string;
}