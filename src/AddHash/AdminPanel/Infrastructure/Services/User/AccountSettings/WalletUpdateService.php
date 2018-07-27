<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletIsNotValidException;

class WalletUpdateService implements WalletUpdateServiceInterface
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

    /**
     * @param WalletUpdateCommandInterface $command
     * @return array
     * @throws UserWalletIsNotValidException
     */
	public function execute(WalletUpdateCommandInterface $command): array
	{
	    $walletsCommand = $command->getWallets();
	    $walletsName = [];

	    foreach ($walletsCommand as $walletCommand) {
            $walletsName[$walletCommand['id']] = $walletCommand['name'];
        }

        $ids = array_keys($walletsName);
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $wallets = $this->userWalletRepository->getByIdsAndUsertId($ids, $userId);

	    if (count($ids) != count($wallets)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        foreach ($wallets as $wallet) {
            $id = $wallet->getId();
            $wallet->setName($walletsName[$id]);
            $this->userWalletRepository->update();
        }

        return $wallets;
	}
}