<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletIsExistException;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletTypeIsNotExistException;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings\WalletTransform;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletCreateServiceInterface;

class WalletCreateService implements WalletCreateServiceInterface
{
    private $walletRepository;

    private $userWalletRepository;

    private $walletTypeRepository;

    private $authenticationService;

	public function __construct(
        WalletRepositoryInterface $walletRepository,
	    UserWalletRepositoryInterface $userWalletRepository,
        WalletTypeRepositoryInterface $walletTypeRepository,
        UserGetAuthenticationServiceInterface $authenticationService
    )
	{
	    $this->walletRepository = $walletRepository;
        $this->userWalletRepository = $userWalletRepository;
        $this->walletTypeRepository = $walletTypeRepository;
        $this->authenticationService = $authenticationService;
	}

    /**
     * @param WalletCreateCommandInterface $command
     * @return array
     * @throws WalletIsExistException
     * @throws WalletTypeIsNotExistException
     */
	public function execute(WalletCreateCommandInterface $command): array
	{
        $type = $command->getTypeId();
        $walletType = $this->walletTypeRepository->getById($type);

        if (null === $walletType) {
            throw new WalletTypeIsNotExistException(['typeId' => ['Invalid type Id']]);
        }

        $value = $command->getValue();
        $wallet = $this->walletRepository->getByValueAndType($value, $type);

        if (null !== $wallet) {
            throw new WalletIsExistException(['value' => ['Wallet already exist']]);
        }

        $wallet = new Wallet($value);
        $wallet->setType($walletType);
        $this->walletRepository->create($wallet);

        /** @var User $user */
        $user = $this->authenticationService->execute();

        $userWallet = new UserWallet();
        $userWallet->setWallet($wallet);
        $userWallet->setUser($user);

        $this->userWalletRepository->create($userWallet);

        return (new WalletTransform())->transform($userWallet);
	}
}