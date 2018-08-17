<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolUpdateCommandInterface
{
    public function getMinerId(): int;

    public function getPoolId(): int;

    public function getUrl(): string;

    public function getUser(): string;
}