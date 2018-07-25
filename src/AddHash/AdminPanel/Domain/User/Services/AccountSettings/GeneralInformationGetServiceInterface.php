<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;

interface GeneralInformationGetServiceInterface
{
	public function execute(User $user): array;
}