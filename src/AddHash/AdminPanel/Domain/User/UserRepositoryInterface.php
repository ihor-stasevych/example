<?php

namespace App\AddHash\AdminPanel\Domain\User;

interface UserRepositoryInterface
{
    public function find(int $id);

    public function create(User $user);
}