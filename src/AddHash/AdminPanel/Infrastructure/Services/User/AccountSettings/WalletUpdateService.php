<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\WalletType;
use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;
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
     */
	public function execute(WalletUpdateCommandInterface $command): array
	{
	    /** TEST */
	    $result = $this->userWalletRepository->getByUnique([1], 2, 'qqqq2wfa2');
	    dd($result);

        $walletsCommand = $command->getWallets();
        $walletsValue = [];
        $walletTypeIds = [];

        foreach ($walletsCommand as $walletCommand) {
            $walletsValue[$walletCommand['id']] = [
                'value'  => $walletCommand['value'],
                'typeId' => $walletCommand['typeId'],
            ];

            if (false === array_search($walletCommand['typeId'], $walletTypeIds)) {
                $walletTypeIds[] = $walletCommand['typeId'];
            }
        }

        $walletTypes = $this->walletTypeRepository->getByIds($walletTypeIds);

        if (count($walletTypes) != count($walletTypeIds)) {
            throw new WalletTypeIsNotExistException('Invalid type Id');
        }

        $ids = array_keys($walletsValue);
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $userWallets = $this->userWalletRepository->getByIdsAndUserId($ids, $userId);

	    if (count($ids) != count($userWallets)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        $walletTypesObject = [];

        /** @var WalletType $walletType */
        foreach ($walletTypes as $walletType) {
            $walletTypesObject[$walletType->getId()] = $walletType;
        }

        $result = [];

        foreach ($userWallets as $userWallet) {
            /** @var UserWallet $userWallet */
            $data = $walletsValue[$userWallet->getId()];
            $userWallet->getWallet()->setValue($data['value']);
            $userWallet->getWallet()->setType($walletTypesObject[$data['typeId']]);

            $this->walletRepository->update();

            $result[] = [
                'id'     => $userWallet->getId(),
                'typeId' => $data['typeId'],
                'value'  => $data['value'],
            ];
        }

        return $result;
	}

	private function checkUniqueNewUserWallet(WalletUpdateCommandInterface $command)
    {
        $uniqueWallets = [];
        $wallets = $command->getWallets();

        foreach ($wallets as $wallet) {
            $uniqueWallets[$wallet['value'] . $wallet['typeId']] = $wallet;
        }

        return count($uniqueWallets) == count($wallets);
    }
}