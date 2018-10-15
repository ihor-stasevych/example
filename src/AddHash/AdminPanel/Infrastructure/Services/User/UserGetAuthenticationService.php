<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Repository\User\UserRepository;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;

class UserGetAuthenticationService implements UserGetAuthenticationServiceInterface
{
    private $authenticationAdapter;

    private $userRepository;

    public function __construct(
        AuthenticationAdapterInterface $authenticationAdapter,
        UserRepository $userRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->userRepository = $userRepository;
    }

    public function execute(): User
    {
        $id = $this->authenticationAdapter->getUserId();

        if (null === $id) {

        }

        $user = $this->userRepository->find($id);


        if (null === $user) {

        }

        return $user;
    }
}