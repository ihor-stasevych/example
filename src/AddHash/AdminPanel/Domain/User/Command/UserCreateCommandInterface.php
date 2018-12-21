<?php

namespace App\AddHash\AdminPanel\Domain\User\Command;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

interface UserCreateCommandInterface
{
    public function getEmail(): Email;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getPhone(): Phone;

    public function getPassword(): string;

    public function getCaptcha(): ?string;
}