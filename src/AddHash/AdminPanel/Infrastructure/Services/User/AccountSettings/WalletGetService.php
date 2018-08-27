<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;

class WalletGetService implements WalletGetServiceInterface
{
    private $tokenStorage;

	public function __construct(TokenStorageInterface $tokenStorage)
	{
        $this->tokenStorage = $tokenStorage;
	}

	public function execute(): array
	{
	    $user = $this->tokenStorage->getToken()->getUser();
	    $data = [];

	    /** @var UserWallet $userWallet */
        foreach ($user->getUserWallets() as $userWallet) {
	        $data[] = [
	            'id'    => $userWallet->getId(),
                'value' => $userWallet->getWallet()->getValue(),
            ];
        }

        return $data;
	}
}