<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;

class WalletGetService implements WalletGetServiceInterface
{
    private $tokenStorage;

	public function __construct(TokenStorageInterface $tokenStorage)
	{
        $this->tokenStorage = $tokenStorage;
	}

	public function execute(): PersistentCollection
	{
	    $user = $this->tokenStorage->getToken()->getUser();

        return $user->getUserWallets();
	}
}