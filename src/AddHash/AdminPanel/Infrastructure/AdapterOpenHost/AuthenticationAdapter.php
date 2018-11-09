<?php

namespace App\AddHash\AdminPanel\Infrastructure\AdapterOpenHost;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserEmailUpdateEmailExistsException;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\Exceptions\AdapterOpenHostUnknownErrorException;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterUserAlreadyExistsException;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserPasswordUpdateInvalidCurrentPasswordException;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\Exceptions\Authentication\AdapterOpenHostChangeEmailEmailExistsException;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\Exceptions\Authentication\AdapterOpenHostInvalidCurrentPasswordException;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\Exceptions\Authentication\AdapterOpenHostRegisterUserAlreadyExistsException;

class AuthenticationAdapter implements AuthenticationAdapterInterface
{
    private $authenticationOpenHost;

    public function __construct(AuthenticationOpenHostInterface $authenticationOpenHost)
    {
        $this->authenticationOpenHost = $authenticationOpenHost;
    }

    public function getUserId(): ?int
    {
        return $this->authenticationOpenHost->getUserId();
    }

    public function getCredentials(): array
    {
        return $this->authenticationOpenHost->getCredentials();
    }

    /**
     * @param array $ids
     * @return array
     * @throws AdapterOpenHostUnknownErrorException
     */
    public function getEmails(array $ids): array
    {
        try {
            $emails = $this->authenticationOpenHost->getEmails($ids);
        } catch (\Exception $e) {
            throw new AdapterOpenHostUnknownErrorException($e->getMessage());
        }

        return $emails;
    }

    /**
     * @param Email $email
     * @throws AdapterOpenHostChangeEmailEmailExistsException
     * @throws AdapterOpenHostUnknownErrorException
     */
    public function changeEmail(Email $email)
    {
        try {
            $this->authenticationOpenHost->changeEmail($email->getEmail());
        } catch (\Exception $e) {
            if ($e instanceof UserEmailUpdateEmailExistsException) {
                throw new AdapterOpenHostChangeEmailEmailExistsException($e->getMessage());
            }

            throw new AdapterOpenHostUnknownErrorException($e->getMessage());
        }
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @throws AdapterOpenHostInvalidCurrentPasswordException
     * @throws AdapterOpenHostUnknownErrorException
     */
    public function changePassword(string $currentPassword, string $newPassword)
    {
        try {
            $this->authenticationOpenHost->changePassword($currentPassword, $newPassword);
        } catch (\Exception $e) {
            if ($e instanceof UserPasswordUpdateInvalidCurrentPasswordException) {
                throw new AdapterOpenHostInvalidCurrentPasswordException($e->getMessage());
            }

            throw new AdapterOpenHostUnknownErrorException($e->getMessage());
        }
    }

    /**
     * @param Email $email
     * @param string $password
     * @param array $role
     * @return array
     * @throws AdapterOpenHostRegisterUserAlreadyExistsException
     * @throws AdapterOpenHostUnknownErrorException
     */
    public function register(Email $email, string $password, array $role): array
    {
        try {
            $data = $this->authenticationOpenHost->register(
                $email->getEmail(),
                $password,
                $role
            );
        } catch (\Exception $e) {
            if ($e instanceof UserRegisterUserAlreadyExistsException) {
                throw new AdapterOpenHostRegisterUserAlreadyExistsException($e->getMessage());
            }

            throw new AdapterOpenHostUnknownErrorException($e->getMessage());
        }

        return $data;
    }
}