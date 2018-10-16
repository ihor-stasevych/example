<?php

namespace App\AddHash\AdminPanel\Domain\User\Password;

interface UserPasswordRecoveryRepositoryInterface
{
	public function save(UserPasswordRecovery $passwordRecovery);

	public function findByHash(?string $hash);

	public function remove(UserPasswordRecovery $passwordRecovery);
}