<?php

namespace App\AddHash\Authentication\Domain\OpenHost;

interface AuthenticationOpenHostInterface
{
    public function getAuthenticationUserId(): ?int;

    public function register(string $email, string $password, array $role): array;
}