<?php

namespace App\AddHash\MinerPanel\Domain\Rig;

use Pagerfanta\Pagerfanta;
use App\AddHash\MinerPanel\Domain\User\User;

interface RigRepositoryInterface
{
    public function getRigsByUser(User $user, ?int $currentPage): ?Pagerfanta;

    public function getRigByIdAndUser(int $id, User $user): ?Rig;
}