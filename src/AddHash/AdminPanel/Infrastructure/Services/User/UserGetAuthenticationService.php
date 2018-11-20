<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Repository\User\UserRepository;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Authentication\UserAuthenticationNoAuthenticationIdException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Authentication\UserAuthenticationInvalidAuthenticationUserException;

final class UserGetAuthenticationService implements UserGetAuthenticationServiceInterface
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

    /**
     * @return User
     * @throws UserAuthenticationInvalidAuthenticationUserException
     * @throws UserAuthenticationNoAuthenticationIdException
     */
    public function execute(): User
    {
        $id = $this->authenticationAdapter->getUserId();

        if (null === $id) {
            throw new UserAuthenticationNoAuthenticationIdException('No authentication ID');
        }

        $user = $this->userRepository->find($id);

        if (null === $user) {
            throw new UserAuthenticationInvalidAuthenticationUserException('Invalid authentication user');
        }

        return $user;
    }
}