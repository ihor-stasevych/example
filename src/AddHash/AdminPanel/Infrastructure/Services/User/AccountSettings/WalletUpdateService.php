<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;

class WalletUpdateService implements WalletUpdateServiceInterface
{
    private $userWalletRepository;

    private $walletRepository;

	public function __construct(UserWalletRepositoryInterface $userWalletRepository, WalletRepositoryInterface $walletRepository)
	{
        $this->userWalletRepository = $userWalletRepository;
        $this->walletRepository = $walletRepository;
	}

	public function execute(WalletUpdateCommandInterface $command): array
	{
        $walletsInput = $command->getWallets();

        $walletIds = array_keys($walletsInput);

        $wallets = $this->walletRepository->getByIds($walletIds);



        $userId = $command->getUser()->getId();
        $this->userWalletRepository->deleteByUserId($userId);

        foreach ($wallets as $walletId => $walletNames) {
            foreach ($walletNames as $name) {
                $userWallet = new UserWallet(
                    null,
                    $userId,
                    $walletId,
                    $name
                );

                $this->userWalletRepository->create($userWallet);
            }
        }

        return $wallets;
	}
}