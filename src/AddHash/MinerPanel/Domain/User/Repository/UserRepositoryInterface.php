<?php

namespace App\AddHash\MinerPanel\Domain\User\Repository;

use App\AddHash\MinerPanel\Domain\User\Model\User;

interface UserRepositoryInterface
{
    public function get(int $id): ?User;

    public function save(User $user): void;
}