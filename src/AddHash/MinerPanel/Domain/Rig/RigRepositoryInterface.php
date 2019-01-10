<?php

namespace App\AddHash\MinerPanel\Domain\Rig;

use Pagerfanta\Pagerfanta;
use App\AddHash\MinerPanel\Domain\User\User;

interface RigRepositoryInterface
{
    public function getRigsByUserWithPagination(User $user, ?int $currentPage): Pagerfanta;

    public function getRigsByUser(User $user): array;

    public function getRigByIdAndUser(int $id, User $user): ?Rig;

    public function getRigById(int $id): ?Rig;

    public function existRigByIdAndUser(int $id, User $user): ?Rig;

    public function getRigByTitleAndUser(string $title, User $user): ?Rig;

    public function save(Rig $rig): void;

    public function delete(Rig $rig): void;
}