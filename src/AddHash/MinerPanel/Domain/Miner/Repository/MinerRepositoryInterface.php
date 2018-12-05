<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Repository;

use App\AddHash\MinerPanel\Domain\User\Model\User;
use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;

interface MinerRepositoryInterface
{
    public function getMinersByUser(User $user): array;

    public function getMinerByIdAndUser(int $id, User $user): ?Miner;

    public function getMinerByTitleAndUser(string $title, User $user): ?Miner;

    public function get(int $id): ?Miner;

    public function save(Miner $miner): void;
}