<?php

namespace App\AddHash\MinerPanel\Domain\Package\Repository;

use App\AddHash\MinerPanel\Domain\Package\Model\Package;

interface PackageRepositoryInterface
{
    public function get(int $id): ?Package;

    public function save(Package $package): void;

    public function getDefaultPackage();
}