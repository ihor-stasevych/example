<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

interface WalletRepositoryInterface
{
    public function getById(int $id): ?Wallet;
}