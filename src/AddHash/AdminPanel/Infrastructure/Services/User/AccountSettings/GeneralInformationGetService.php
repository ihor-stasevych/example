<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationGetServiceInterface;

class GeneralInformationGetService implements GeneralInformationGetServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function execute(): array
	{
        $user = $this->tokenStorage->getToken()->getUser();

        return [
            'email'       => $user->getEmail(),
            'backupEmail' => $user->getBackupEmail(),
            'firstName'   => $user->getFirstName(),
            'lastName'    => $user->getLastName(),
            'phone'       => $user->getPhoneNumber(),
        ];
	}
}