<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationGetServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings\GeneralInformationTransform;

final class GeneralInformationGetService implements GeneralInformationGetServiceInterface
{
    private $authenticationAdapter;

    private $authenticationService;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserGetAuthenticationServiceInterface $authenticationService
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->authenticationService = $authenticationService;
    }

    public function execute(): array
	{
        $user = $this->authenticationService->execute();
        $credentials = $this->authenticationAdapter->getCredentials();

        return (new GeneralInformationTransform)->transform($credentials, $user);
	}
}