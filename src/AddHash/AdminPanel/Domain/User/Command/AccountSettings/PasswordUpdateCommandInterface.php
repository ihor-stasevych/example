<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;

interface PasswordUpdateCommandInterface
{
	public function getCurrentPassword(): string;

    public function getNewPassword(): string;

    public function getConfirmNewPassword(): string;

    public function getUser(): User;
}