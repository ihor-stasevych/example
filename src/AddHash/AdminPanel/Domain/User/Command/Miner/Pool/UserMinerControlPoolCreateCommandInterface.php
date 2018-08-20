<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool;

interface UserMinerControlPoolCreateCommandInterface
{
    public function getMinerId(): int;

    public function getUrl(): string;

    public function getUser(): string;

    public function getPassword(): string;
}