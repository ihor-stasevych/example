<?php

namespace App\AddHash\Authentication\Infrastructure\OpenHost;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\AddHash\Authentication\Application\Command\UserRegisterCommand;
use App\AddHash\Authentication\Domain\Services\UserRegisterServiceInterface;
use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterInvalidInputDataException;

class AuthenticationOpenHost implements AuthenticationOpenHostInterface
{
    private $tokenStorage;

    private $userRegisterService;

    private $validator;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRegisterServiceInterface $userRegisterService,
        ValidatorInterface $validator
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRegisterService = $userRegisterService;
        $this->validator = $validator;
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

    /**
     * @param string $email
     * @param string $password
     * @param array $role
     * @return array
     * @throws UserRegisterInvalidInputDataException
     */
    public function register(string $email, string $password, array $role): array
    {
        $command = new UserRegisterCommand($email, $password, $role);

        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new UserRegisterInvalidInputDataException('Invalid input data');
        }

        return $this->userRegisterService->execute($command);
    }
}