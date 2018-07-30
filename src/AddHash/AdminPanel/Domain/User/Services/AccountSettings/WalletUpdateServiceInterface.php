<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;

interface WalletUpdateServiceInterface
{
	public function execute(WalletUpdateCommandInterface $command): array;
}