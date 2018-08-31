<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletType;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletIsExistException;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletTypeIsNotExistException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletIsNotValidException;

class WalletUpdateService implements WalletUpdateServiceInterface
{
    private $userWalletRepository;

    private $walletRepository;

    private $walletTypeRepository;

    private $tokenStorage;

	public function __construct(
	    UserWalletRepositoryInterface $userWalletRepository,
        WalletRepositoryInterface $walletRepository,
        WalletTypeRepositoryInterface $walletTypeRepository,
        TokenStorageInterface $tokenStorage
    )
	{
        $this->userWalletRepository = $userWalletRepository;
        $this->walletRepository = $walletRepository;
        $this->walletTypeRepository = $walletTypeRepository;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param WalletUpdateCommandInterface $command
     * @return array
     * @throws UserWalletIsNotValidException
     * @throws WalletTypeIsNotExistException
     * @throws WalletIsExistException
     */
	public function execute(WalletUpdateCommandInterface $command): array
	{
        $walletsCommand = $command->getWallets();
        $walletsValue = [];
        $walletsTypeId = [];
        $userWalletsId = [];
        $uniqueWallets = [];

        foreach ($walletsCommand as $walletCommand) {
            $walletsValue[$walletCommand['id']] = [
                'value'  => $walletCommand['value'],
                'typeId' => $walletCommand['typeId'],
            ];

            $userWalletsId[] = $walletCommand['id'];
            $uniqueWallets[] = $walletCommand['value'] . $walletCommand['typeId'];

            if (false === array_search($walletCommand['typeId'], $walletsTypeId)) {
                $walletsTypeId[] = $walletCommand['typeId'];
            }
        }

        if (count(array_unique($uniqueWallets)) != count($walletsValue)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $userWallets = $this->userWalletRepository->getByIdsAndUserId($userWalletsId, $userId);

	    if (count($userWalletsId) != count($userWallets)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        foreach ($walletsValue as $walletValue) {

            $uw = $this->userWalletRepository->getByUnique($userWalletsId, $walletValue['typeId'], $walletValue['value']);

            if (null !== $uw) {
                throw new WalletIsExistException('Wallet already exist');
            }
        }

        $walletsType = $this->walletTypeRepository->getByIds($walletsTypeId);

        if (count($walletsType) != count($walletsTypeId)) {
            throw new WalletTypeIsNotExistException('Invalid type Id');
        }

        $walletsTypeObject = [];

        /** @var WalletType $walletType */
        foreach ($walletsType as $walletType) {
            $walletsTypeObject[$walletType->getId()] = $walletType;
        }

        $result = [];

        foreach ($userWallets as $userWallet) {
            /** @var UserWallet $userWallet */
            $data = $walletsValue[$userWallet->getId()];
            $userWallet->getWallet()->setValue($data['value']);
            $userWallet->getWallet()->setType($walletsTypeObject[$data['typeId']]);

            $this->walletRepository->update();

            $result[] = [
                'id'     => $userWallet->getId(),
                'typeId' => $data['typeId'],
                'value'  => $data['value'],
            ];
        }

        return $result;
	}
}