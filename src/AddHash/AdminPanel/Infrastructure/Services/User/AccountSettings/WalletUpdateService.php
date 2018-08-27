<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletIsNotValidException;

class WalletUpdateService implements WalletUpdateServiceInterface
{
    private $userWalletRepository;

    private $walletRepository;

    private $tokenStorage;

	public function __construct(
	    UserWalletRepositoryInterface $userWalletRepository,
        WalletRepositoryInterface $walletRepository,
        TokenStorageInterface $tokenStorage
    )
	{
        $this->userWalletRepository = $userWalletRepository;
        $this->walletRepository = $walletRepository;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param WalletUpdateCommandInterface $command
     * @return array
     * @throws UserWalletIsNotValidException
     */
	public function execute(WalletUpdateCommandInterface $command): array
	{
	    $walletsCommand = $command->getWallets();
	    $walletsValue = [];

	    foreach ($walletsCommand as $walletCommand) {
            $walletsValue[$walletCommand['id']] = $walletCommand['value'];
        }

        $ids = array_keys($walletsValue);
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $userWallets = $this->userWalletRepository->getByIdsAndUserId($ids, $userId);

	    if (count($ids) != count($userWallets)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        $result = [];

        foreach ($userWallets as $userWallet) {
            /** @var UserWallet $userWallet */
            $value = $walletsValue[$userWallet->getId()];
            $userWallet->getWallet()->setValue($value);

            $this->walletRepository->update();

            $result[] = [
                'id'    => $userWallet->getId(),
                'value' => $value,
            ];
        }

        return $result;
	}
}