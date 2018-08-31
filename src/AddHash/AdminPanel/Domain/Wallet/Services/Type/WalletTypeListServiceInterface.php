<?php

namespace App\AddHash\AdminPanel\Domain\Wallet\Services\Type;

interface WalletTypeListServiceInterface
{
    public function execute(): array;
}