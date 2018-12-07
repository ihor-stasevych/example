<?php

namespace App\AddHash\MinerPanel\Domain\Miner;

use Pagerfanta\Pagerfanta;
use App\AddHash\MinerPanel\Domain\User\User;

interface MinerRepositoryInterface
{
    public function getMinersByUser(User $user, ?int $currentPage): ?Pagerfanta;

    public function getMinerByIdAndUser(int $id, User $user): ?Miner;

    public function getMinerByTitleAndUser(string $title, User $user): ?Miner;

    public function getCountByUser(User $user): int;

    public function get(int $id): ?Miner;

    public function save(Miner $miner): void;

    public function delete(Miner $miner): void;
}