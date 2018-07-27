<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

interface WalletGetServiceInterface
{
	public function execute(): array;
}