<?php

namespace App\AddHash\MinerPanel\Domain\AdapterOpenHost;

interface AuthenticationAdapterInterface
{
    public function getUserId(): ?int;
}