<?php

namespace App\AddHash\Authentication\Domain\Repository;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\Authentication\Domain\Model\UserPasswordRecovery;

interface UserPasswordRecoveryRepositoryInterface
{
    public function findByUser(User $user): ?UserPasswordRecovery;

    public function findByHash(?string $hash): ?UserPasswordRecovery;

    public function remove(UserPasswordRecovery $passwordRecovery);

    public function save(UserPasswordRecovery $passwordRecovery);
}