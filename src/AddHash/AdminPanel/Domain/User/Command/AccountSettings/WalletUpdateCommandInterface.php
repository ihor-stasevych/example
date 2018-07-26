<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;

interface WalletUpdateCommandInterface
{
	public function getWallets(): array;

    public function getUser(): User;
}