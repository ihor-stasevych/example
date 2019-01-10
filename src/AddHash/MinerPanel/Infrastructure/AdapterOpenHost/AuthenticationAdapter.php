<?php

namespace App\AddHash\MinerPanel\Infrastructure\AdapterOpenHost;

use App\AddHash\Authentication\Domain\OpenHost\AuthenticationOpenHostInterface;
use App\AddHash\MinerPanel\Domain\AdapterOpenHost\AuthenticationAdapterInterface;

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
}