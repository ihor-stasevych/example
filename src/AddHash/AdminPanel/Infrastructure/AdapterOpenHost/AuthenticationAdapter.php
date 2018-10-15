<?php

namespace App\AddHash\AdminPanel\Infrastructure\AdapterOpenHost;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use App\AddHash\AdminPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;

class AuthenticationAdapter implements AuthenticationAdapterInterface
{
    private $authenticationOpenHost;

    public function __construct(AuthenticationOpenHostInterface $authenticationOpenHost)
    {
        $this->authenticationOpenHost = $authenticationOpenHost;
    }

    public function getUserId(): ?int
    {
        return $this->authenticationOpenHost->getAuthenticationUserId();
    }

    /**
     * @param Email $email
     * @param string $password
     * @param array $role
     * @return array
     * @throws \Exception
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
            throw $e;
        }

        return $data;
    }
}