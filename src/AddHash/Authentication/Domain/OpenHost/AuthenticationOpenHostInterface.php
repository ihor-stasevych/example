<?php

namespace App\AddHash\Authentication\Domain\OpenHost;

interface AuthenticationOpenHostInterface
{
    public function getUserId(): ?int;

    public function getCredentials(): array;

    public function getEmails(array $ids): array;

    public function changeEmail(string $email);

    public function changePassword(string $currentPassword, string $newPassword);

    public function register(string $email, string $password, array $role): array;
}