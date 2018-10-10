<?php

namespace App\AddHash\Authentication\Domain\Command;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserRegisterCommandInterface
{
    public function getEmail(): Email;

    public function getPassword(): string;

    public function getRoles(): array;

    public function getCaptcha(): ?string;

    public function isValidRoles(): bool;
}