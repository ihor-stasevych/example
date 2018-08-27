<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

interface WalletRepositoryInterface
{
    public function getById(int $id): ?Wallet;

    public function getByValueAndType(string $value, int $typeId): ?Wallet;

    public function update();

    public function create(Wallet $wallet);
}