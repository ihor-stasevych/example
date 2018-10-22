<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\GeneralInformationUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationUpdateServiceInterface;
use App\AddHash\AdminPanel\Infrastructure\Transformers\User\AccountSettings\GeneralInformationTransform;

final class GeneralInformationUpdateService implements GeneralInformationUpdateServiceInterface
{
    private $authenticationAdapter;

    private $authenticationService;

    private $userRepository;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserGetAuthenticationServiceInterface $authenticationService,
        UserRepositoryInterface $userRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
    }

	public function execute(GeneralInformationUpdateCommandInterface $command): array
	{
	    $user = $this->authenticationService->execute();

        $this->authenticationAdapter->changeEmail($command->getEmail());

        $user->setBackupEmail($command->getBackupEmail());
        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setPhoneNumber($command->getPhoneNumber());

        $this->userRepository->create($user);

        return (new GeneralInformationTransform())->transform(
            $this->authenticationAdapter->getCredentials(),
            $user
        );
	}
}