<?php

namespace App\AddHash\AdminPanel\Domain\AdapterOpenHost;

use App\AddHash\System\GlobalContext\ValueObject\Email;

interface AuthenticationAdapterInterface
{
    public function getUserId(): ?int;

    public function getCredentials(): array;

    public function getEmails(array $ids): array;

    public function changeEmail(Email $email);

    public function changePassword(string $currentPassword, string $newPassword);

    public function register(Email $email, string $password, array $role): array;
}