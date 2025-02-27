<?php

namespace App\AddHash\MinerPanel\Domain\Miner;

use Pagerfanta\Pagerfanta;
use App\AddHash\MinerPanel\Domain\User\User;

interface MinerRepositoryInterface
{
    public function getAll(): array;

    public function getMinersByUserWithPagination(User $user, ?int $currentPage): Pagerfanta;

    public function getMinersByUser(User $user): array;

    public function getCountMinersByUserGroupByType(User $user): array;

    public function getCountAndAvgHashRateActiveMinersByUserGroupByType(User $user): array;

    public function getAvgHashRateActiveMinersByUserGroupByAlgorithm(User $user): array;

    public function getAvgHashRatesMiners(\DateTime $date): array;

    public function getMinerByIdAndUser(int $id, User $user): ?Miner;

    public function existMinerByIdAndUser(int $id, User $user): ?Miner;

    public function existMinerByIdAndUserForDelete(int $id, User $user): ?Miner;

    public function getMinerByTitleAndUser(string $title, User $user): ?Miner;

    public function getCountByUser(User $user): int;

    public function getMinersWithoutRigs(array $ids, User $user): array;

    public function getMinersByIdsAndUser(array $ids, User $user): array;

    public function getMinersStatusByIdsAndUser(array $ids, User $user): array;

    public function getMinerByStatusPool(int $statusPool): array;

    public function get(int $id): ?Miner;

    public function getMinerAndPools(int $id): ?Miner;

    public function save(Miner $miner): void;

    public function saveAll(array $miners): void;

    public function delete(Miner $miner): void;
}