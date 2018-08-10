<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletIsNotExistException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletExistException;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletCreateServiceInterface;

class WalletCreateService implements WalletCreateServiceInterface
{
    private $walletRepository;

    private $userWalletRepository;

    private $tokenStorage;

	public function __construct(
        WalletRepositoryInterface $walletRepository,
	    UserWalletRepositoryInterface $userWalletRepository,
        TokenStorageInterface $tokenStorage
    )
	{
	    $this->walletRepository = $walletRepository;
        $this->userWalletRepository = $userWalletRepository;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param WalletCreateCommandInterface $command
     * @return UserWallet
     * @throws UserWalletExistException
     * @throws WalletIsNotExistException
     */
	public function execute(WalletCreateCommandInterface $command): UserWallet
	{
        $wallet = $this->walletRepository->getById($command->getWalletId());

        if (null === $wallet) {
            throw new WalletIsNotExistException('Wallet id is not valid');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $userWallet = $this->userWalletRepository->getUniqueWallet(
            $command->getValue(),
            $command->getWalletId(),
            $user->getId()
        );

        if (null !== $userWallet) {
            throw new UserWalletExistException('User wallet already taken');
        }

        $userWallet = new UserWallet($command->getValue());
        $userWallet->setWallet($wallet);
        $userWallet->setUser($user);

        $this->userWalletRepository->create($userWallet);

        return $userWallet;
	}
}