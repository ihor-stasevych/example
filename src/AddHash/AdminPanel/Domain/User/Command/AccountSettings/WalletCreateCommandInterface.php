<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

interface WalletCreateCommandInterface
{
	public function getWalletId(): int;

	public function getValue(): string;
}