<?php

namespace App\AddHash\Authentication\Infrastructure\OpenHost;

use App\AddHash\Authentication\Application\Command\UserRegisterCommand;
use App\AddHash\Authentication\Domain\Services\UserRegisterServiceInterface;
use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationOpenHost implements AuthenticationOpenHostInterface
{
    private $tokenStorage;

    private $userRegisterService;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRegisterServiceInterface $userRegisterService
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRegisterService = $userRegisterService;
    }

    public function getAuthenticationUserId(): ?int
    {
        $token = $this->tokenStorage->getToken();

        $userId = null;

        if (null !== $token) {
            $userId = $token->getUser()->getId();
        }

        return $userId;
    }

    public function register(string $email, string $password, array $role): array
    {
        $command = new UserRegisterCommand($email, $password, $role);

        return $this->userRegisterService->execute($command);
    }
}