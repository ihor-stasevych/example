<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\User;

use App\AddHash\MinerPanel\Domain\User\Model\User;
use App\AddHash\MinerPanel\Domain\User\Repository\UserRepositoryInterface;
use App\AddHash\MinerPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

class UserAuthenticationGetService implements UserAuthenticationGetServiceInterface
{
    private $authenticationAdapter;

    private $userRepository;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserRepositoryInterface $userRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->userRepository = $userRepository;
    }

    public function execute(): User
    {
        $id = $this->authenticationAdapter->getUserId();

        $user = $this->userRepository->get($id);

        if (null === $user) {
            $user = new User($id);
            $this->userRepository->save($user);
        }

        return $user;
    }
}