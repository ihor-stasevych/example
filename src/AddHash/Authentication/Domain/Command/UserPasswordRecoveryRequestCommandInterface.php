<?php

namespace App\AddHash\Authentication\Domain\Command;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserPasswordRecoveryRequestCommandInterface
{
    public function getEmail(): Email;
}