<?php

namespace App\AddHash\AdminPanel\Domain\AdapterOpenHost;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface AuthenticationAdapterInterface
{
    public function register(Email $email, string $password, array $role): array;

    public function getUserId(): ?int;
}