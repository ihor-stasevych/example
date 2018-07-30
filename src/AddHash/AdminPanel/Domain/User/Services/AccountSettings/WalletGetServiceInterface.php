<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use Doctrine\ORM\PersistentCollection;

interface WalletGetServiceInterface
{
	public function execute(): PersistentCollection;
}