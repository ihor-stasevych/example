<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

interface WalletUpdateCommandInterface
{
	public function getWallets(): array;

    public function isInteger($value): bool;
}