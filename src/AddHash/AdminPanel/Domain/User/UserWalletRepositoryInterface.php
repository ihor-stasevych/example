<?php

namespace App\AddHash\AdminPanel\Domain\User;

interface UserWalletRepositoryInterface
{
    public function getByIdsAndUserId(array $ids, int $userId): array;

    public function getByValueAndWalletIdAndUserId(string $value, int $walletId, int $userId): ?UserWallet;

    public function update();

    public function create(UserWallet $userWallet);
}