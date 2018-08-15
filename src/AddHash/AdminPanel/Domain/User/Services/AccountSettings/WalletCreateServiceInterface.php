<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;

interface WalletCreateServiceInterface
{
	public function execute(WalletCreateCommandInterface $command): Wallet;
}