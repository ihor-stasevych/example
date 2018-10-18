<?php

namespace App\AddHash\Authentication\Infrastructure\OpenHost;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\AddHash\Authentication\Application\Command\UserRegisterCommand;
use App\AddHash\Authentication\Application\Command\UserEmailUpdateCommand;
use App\AddHash\Authentication\Domain\Services\UserRegisterServiceInterface;
use App\AddHash\Authentication\Application\Command\UserPasswordUpdateCommand;
use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use App\AddHash\Authentication\Domain\Services\UserEmailUpdateServiceInterface;
use App\AddHash\Authentication\Domain\Services\UserPasswordUpdateServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterInvalidInputDataException;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserEmailUpdateInvalidInputDataException;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserPasswordUpdateInvalidInputDataException;

class AuthenticationOpenHost implements AuthenticationOpenHostInterface
{
    private $tokenStorage;

    private $userRegisterService;

    private $userEmailUpdateService;

    private $passwordUpdateService;

    private $validator;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRegisterServiceInterface $userRegisterService,
        ValidatorInterface $validator,
        UserEmailUpdateServiceInterface $userEmailUpdateService,
        UserPasswordUpdateServiceInterface $passwordUpdateService
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRegisterService = $userRegisterService;
        $this->userEmailUpdateService = $userEmailUpdateService;
        $this->passwordUpdateService = $passwordUpdateService;
        $this->validator = $validator;
    }

    public function getUserId(): ?int
    {
        $credentials = $this->getCredentials();
        $userId = null;

        if (false === empty($credentials)) {
            $userId = $credentials['id'];
        }

        return $userId;
    }

    public function getCredentials(): array
    {
        $token = $this->tokenStorage->getToken();

        $data = [];

        if (null !== $token) {
            /** @var User $user */
            $user = $token->getUser();

            $data['id'] = $user->getId();
            $data['email'] = $user->getEmail();
            $data['roles'] = $user->getRoles();
        }

        return $data;
    }

    /**
     * @param string $email
     * @throws UserEmailUpdateInvalidInputDataException
     */
    public function changeEmail(string $email)
    {
        $command = new UserEmailUpdateCommand($email);

        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new UserEmailUpdateInvalidInputDataException('Invalid input data');
        }

        $this->userEmailUpdateService->execute($command);
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @throws UserPasswordUpdateInvalidInputDataException
     */
    public function changePassword(string $currentPassword, string $newPassword)
    {
        $command = new UserPasswordUpdateCommand($currentPassword, $newPassword);

        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new UserPasswordUpdateInvalidInputDataException('Invalid input data');
        }

        $this->passwordUpdateService->execute($command);
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