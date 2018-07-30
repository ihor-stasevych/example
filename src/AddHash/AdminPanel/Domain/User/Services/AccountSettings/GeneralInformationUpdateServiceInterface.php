<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\GeneralInformationUpdateCommandInterface;

interface GeneralInformationUpdateServiceInterface
{
	public function execute(GeneralInformationUpdateCommandInterface $command): array;
}