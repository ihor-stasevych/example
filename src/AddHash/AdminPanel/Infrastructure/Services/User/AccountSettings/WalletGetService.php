<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletGetServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings\WalletTransform;

final class WalletGetService implements WalletGetServiceInterface
{
    private $authenticationService;

    private $userWalletRepository;

	public function __construct(
	    UserGetAuthenticationServiceInterface $authenticationService,
        UserWalletRepositoryInterface $userWalletRepository
    )
	{
        $this->authenticationService = $authenticationService;
        $this->userWalletRepository = $userWalletRepository;
	}

	public function execute(): array
	{
	    $user = $this->authenticationService->execute();
        $data = [];
        $userWallets = $this->userWalletRepository->getByUserId($user);

        /** @var UserWallet $userWallet */
        foreach ($userWallets as $userWallet) {
	        $data[] = (new WalletTransform())->transform($userWallet);
        }

        return $data;
	}
}