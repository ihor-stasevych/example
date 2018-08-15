<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

interface WalletRepositoryInterface
{
    public function getById(int $id): ?Wallet;

    public function getByValue(string $value): ?Wallet;

    public function update();

    public function create(Wallet $wallet);
}