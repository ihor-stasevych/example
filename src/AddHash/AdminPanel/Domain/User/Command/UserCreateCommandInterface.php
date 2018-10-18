<?php

namespace App\AddHash\AdminPanel\Domain\User\Command;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserCreateCommandInterface
{
    public function getEmail(): Email;

    public function getPassword(): string;

    public function getCaptcha(): ?string;
}