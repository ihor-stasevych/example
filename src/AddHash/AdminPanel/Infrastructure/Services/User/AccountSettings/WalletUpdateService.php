<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletType;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\UserWalletRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletIsExistException;
use App\AddHash\AdminPanel\Domain\Wallet\Exceptions\WalletTypeIsNotExistException;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings\WalletTransform;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\WalletUpdateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\UserWalletIsNotValidException;

final class WalletUpdateService implements WalletUpdateServiceInterface
{
    private $userWalletRepository;

    private $walletRepository;

    private $walletTypeRepository;

    private $authenticationService;

	public function __construct(
	    UserWalletRepositoryInterface $userWalletRepository,
        WalletRepositoryInterface $walletRepository,
        WalletTypeRepositoryInterface $walletTypeRepository,
        UserGetAuthenticationServiceInterface $authenticationService
    )
	{
        $this->userWalletRepository = $userWalletRepository;
        $this->walletRepository = $walletRepository;
        $this->walletTypeRepository = $walletTypeRepository;
        $this->authenticationService = $authenticationService;
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

        foreach ($walletsCommand as $position => $walletCommand) {
            $walletsValue[$walletCommand['id']] = [
                'value'  => $walletCommand['value'],
                'typeId' => $walletCommand['typeId'],
            ];

            $userWalletsId[] = $walletCommand['id'];
            $uniqueWallets[] = $walletCommand['value'] . $walletCommand['typeId'];
            $walletsTypeId[] = $walletCommand['typeId'];
        }

        $user = $this->authenticationService->execute();
        $userWallets = $this->userWalletRepository->getByIdsAndUserId($userWalletsId, $user);

	    if (count($userWalletsId) != count($userWallets)) {
            throw new UserWalletIsNotValidException('User wallet is not valid');
        }

        $walletsTypeId = array_unique($walletsTypeId);
        $walletsType = $this->walletTypeRepository->getByIds($walletsTypeId);

        if (count($walletsType) != count($walletsTypeId)) {
            throw new WalletTypeIsNotExistException('Invalid type Id');
        }

        $this->checkNotUniqueValue($uniqueWallets, $walletsCommand);

        $this->checkNotUniqueValueInRepository($walletsValue, $userWalletsId, $walletsCommand);

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

            $result[] = (new WalletTransform())->transform($userWallet);
        }

        return $result;
	}

    /**
     * @param array $uniqueWallets
     * @param array $walletsCommand
     * @throws WalletIsExistException
     */
	private function checkNotUniqueValue(array $uniqueWallets, array $walletsCommand)
    {
        $errorsNotUnique = [];
        $countValueWallets = array_count_values($uniqueWallets);

        foreach ($countValueWallets as $walletValue => $walletCount) {
            if ($walletCount > 1) {
                foreach ($walletsCommand as $keyWalletCommand => $walletCommand) {
                    if ($walletCommand['value'] . $walletCommand['typeId'] == $walletValue) {
                        $errorsNotUnique['wallets['. $keyWalletCommand .'][value]'] = ['Not unique value'];
                    }
                }
            }
        }

        if (false === empty($errorsNotUnique)) {
            throw new WalletIsExistException($errorsNotUnique);
        }
    }

    /**
     * @param array $walletsValue
     * @param array $userWalletsId
     * @param array $walletsCommand
     * @throws WalletIsExistException
     */
    private function checkNotUniqueValueInRepository(array $walletsValue, array $userWalletsId, array $walletsCommand)
    {
        $errorsNotUnique = [];
        $errorsValue = [];

        foreach ($walletsValue as $walletId => $walletValue) {
            $uw = $this->userWalletRepository->getByUnique($userWalletsId, $walletValue['typeId'], $walletValue['value']);

            if (null !== $uw) {
                $errorsValue[] = $uw->getWallet()->getValue();
            }
        }

        foreach ($walletsCommand as $position => $walletCommand) {
            if (true === in_array($walletCommand['value'], $errorsValue)) {
                $errorsNotUnique['wallets['. $position .'][value]'] = ['Not unique value'];
            }
        }

        if (false === empty($errorsNotUnique)) {
            throw new WalletIsExistException($errorsNotUnique);
        }
    }
}