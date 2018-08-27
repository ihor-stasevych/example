<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletIsExistException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
     * @return array
     * @throws WalletIsExistException
     */
	public function execute(WalletCreateCommandInterface $command): array
	{
        $value = $command->getValue();

        $wallet = $this->walletRepository->getByValue($value);

        if (null !== $wallet) {
            throw new WalletIsExistException('Wallet already exist');
        }

        $wallet = new Wallet($value);
        $this->walletRepository->create($wallet);

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $userWallet = new UserWallet();
        $userWallet->setWallet($wallet);
        $userWallet->setUser($user);

        $this->userWalletRepository->create($userWallet);

        return [
            'id'    => $userWallet->getId(),
            'value' => $value
        ];
	}
}