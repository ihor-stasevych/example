<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\PasswordUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\PasswordUpdateServiceInterface;

class PasswordUpdateService implements PasswordUpdateServiceInterface
{
    private $authenticationAdapter;

	public function __construct(AuthenticationAdapterInterface $authenticationAdapter)
	{
        $this->authenticationAdapter = $authenticationAdapter;
	}

	public function execute(PasswordUpdateCommandInterface $command)
	{
        $this->authenticationAdapter->changePassword(
            $command->getCurrentPassword(),
            $command->getNewPassword()
        );
	}
}