<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

interface WalletTypeRepositoryInterface
{
    public function getById(int $id): ?WalletType;

    public function getByIds(array $ids): array;

    public function findAll(): array;
}