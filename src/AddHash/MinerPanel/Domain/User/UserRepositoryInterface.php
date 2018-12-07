<?php

namespace App\AddHash\MinerPanel\Domain\User;

interface UserRepositoryInterface
{
    public function get(int $id): ?User;

    public function save(User $user): void;
}