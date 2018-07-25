<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationGetServiceInterface;

class GeneralInformationGetService implements GeneralInformationGetServiceInterface
{
	public function execute(User $user): array
	{
        return [
            'email'       => $user->getEmail(),
            'backupEmail' => $user->getBackupEmail(),
            'firstName'   => $user->getFirstName(),
            'lastName'    => $user->getLastName(),
            'phone'       => $user->getPhoneNumber(),
        ];
	}
}