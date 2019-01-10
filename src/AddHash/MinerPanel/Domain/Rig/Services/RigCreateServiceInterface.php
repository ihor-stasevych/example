<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Services;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigCreateCommandInterface;

interface RigCreateServiceInterface
{
    public function execute(RigCreateCommandInterface $command): array;
}