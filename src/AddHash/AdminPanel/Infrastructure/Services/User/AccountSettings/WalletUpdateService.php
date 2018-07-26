<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;


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
	    $this->userWalletRepository->deleteByUserId();
        $wallets = $command->getWallets();
        $data = [];

        if ($wallets) {
            foreach ($wallets as $walletId => $walletNames) {
                if (!is_array($walletNames)) {

                }
                foreach ($walletNames as $name) {
                    if (!$name) {
                        continue;
                    }

                    $data[] = [
                        'walletId' => $walletId,
                        'name'     => $name,
                    ];
                }
            }
        }

        foreach ($data as $item) {
            $userWallet = new UserWallet(null, $command->getUser()->getId(), $item['walletId'], $item['name']);
            $this->userWalletRepository->create($userWallet);
        }

	}
}