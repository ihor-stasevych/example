<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\User;

use App\AddHash\MinerPanel\Domain\Package\Repository\PackageRepositoryInterface;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\MinerPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class UserAuthenticationGetService implements UserAuthenticationGetServiceInterface
{
    private $authenticationAdapter;

    private $userRepository;

    private $packageRepository;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserRepositoryInterface $userRepository,
		PackageRepositoryInterface $packageRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->userRepository = $userRepository;
        $this->packageRepository = $packageRepository;
    }

    public function execute(): User
    {
        $id = $this->authenticationAdapter->getUserId();

        $user = $this->userRepository->get($id);

        if ($user) {
        	return $user;
        }

	    $pack = $this->packageRepository->getDefaultPackage();
	    $user = new User($id);
	    $user->setPackage($pack);
	    $this->userRepository->save($user);

        return $user;
    }
}