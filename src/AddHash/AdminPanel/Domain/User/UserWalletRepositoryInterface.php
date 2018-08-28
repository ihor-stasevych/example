<?php

namespace App\AddHash\AdminPanel\Domain\User;

interface UserWalletRepositoryInterface
{
    public function getByIdsAndUserId(array $ids, int $userId): array;

    public function update();

    public function create(UserWallet $userWallet);

    public function getByUnique(array $ids, int $typeId, string $value);
}