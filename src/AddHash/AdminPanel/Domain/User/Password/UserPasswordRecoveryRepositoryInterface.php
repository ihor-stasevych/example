<?php

namespace App\AddHash\AdminPanel\Domain\User\Password;

use App\AddHash\Authentication\Domain\Model\User;

interface UserPasswordRecoveryRepositoryInterface
{
	public function save(UserPasswordRecovery $passwordRecovery);

	public function findByHash(?string $hash);

	public function findByUser(User $user);

	public function remove(UserPasswordRecovery $passwordRecovery);
}