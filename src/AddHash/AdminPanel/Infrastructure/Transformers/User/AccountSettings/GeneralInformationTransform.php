<?php

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;

class GeneralInformationTransform
{
    public function transform(array $credentials, User $user): array
    {
        return [
            'email'       => (false === empty($credentials['email'])) ? $credentials['email'] : '',
            'backupEmail' => $user->getBackupEmail(),
            'firstName'   => $user->getFirstName(),
            'lastName'    => $user->getLastName(),
            'phone'       => $user->getPhoneNumber(),
        ];
    }
}