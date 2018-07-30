<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;

class WalletGetService implements WalletGetServiceInterface
{
    private $userWalletRepository;

    private $tokenStorage;

	public function __construct(
	    UserWalletRepositoryInterface $userWalletRepository,
        TokenStorageInterface $tokenStorage
    )
	{
        $this->userWalletRepository = $userWalletRepository;
        $this->tokenStorage = $tokenStorage;
	}

	public function execute(): array
	{
        $userId = $this->tokenStorage->getToken()->getUser()->getId();

        return $this->userWalletRepository->getByUserId($userId);
	}
}