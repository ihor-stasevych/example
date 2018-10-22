<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

interface WalletCreateCommandInterface
{
	public function getValue(): string;

    public function getTypeId(): int;

    public function isInteger($value): bool;
}