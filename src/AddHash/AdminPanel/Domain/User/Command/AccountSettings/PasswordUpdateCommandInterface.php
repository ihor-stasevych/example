<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\AccountSettings;

interface PasswordUpdateCommandInterface
{
	public function getCurrentPassword(): string;

    public function getNewPassword(): string;

    public function getConfirmNewPassword(): string;

    public function comparePasswords(): bool;
}