<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Services;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigGetCommandInterface;

interface RigGetServiceInterface
{
    public function execute(RigGetCommandInterface $command): array;
}