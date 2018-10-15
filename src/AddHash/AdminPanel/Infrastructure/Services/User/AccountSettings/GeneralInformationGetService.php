<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationGetServiceInterface;

class GeneralInformationGetService implements GeneralInformationGetServiceInterface
{
    private $tokenStorage;

    private $authenticationService;

    public function __construct(TokenStorageInterface $tokenStorage, UserGetAuthenticationServiceInterface $authenticationService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationService = $authenticationService;
    }

    public function execute(): array
	{
        $user = $this->authenticationService->execute();

//        dd($user);
//
//
//        return [
//            'email'       => $user->getEmail(),
//            'backupEmail' => $user->getBackupEmail(),
//            'firstName'   => $user->getFirstName(),
//            'lastName'    => $user->getLastName(),
//            'phone'       => $user->getPhoneNumber(),
//        ];
	}
}