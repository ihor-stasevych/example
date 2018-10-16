<?php

namespace App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserWallet;

class WalletTransform
{
    public function transform(UserWallet $userWallet): array
    {
        return [
            'id'     => $userWallet->getId(),
            'typeId' => $userWallet->getWallet()->getType()->getId(),
            'value'  => $userWallet->getWallet()->getValue(),
        ];
    }
}