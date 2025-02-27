<?php

namespace App\AddHash\AdminPanel\Domain\User;

interface UserWalletRepositoryInterface
{
    public function getByIdsAndUserId(array $ids, User $user): array;

    public function update();

    public function create(UserWallet $userWallet);

    public function getByUserId(User $user): array;

    public function getByUnique(array $ids, int $typeId, string $value): ?UserWallet;
}