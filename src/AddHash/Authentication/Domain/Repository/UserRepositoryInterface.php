<?php

namespace App\AddHash\Authentication\Domain\Repository;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;

interface UserRepositoryInterface
{
    public function getByEmail(Email $email): ?User;

    public function getByIds(array $ids): array;

    public function save(User $user);
}