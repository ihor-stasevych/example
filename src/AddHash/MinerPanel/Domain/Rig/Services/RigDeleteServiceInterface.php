<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Services;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigDeleteCommandInterface;

interface RigDeleteServiceInterface
{
    public function execute(RigDeleteCommandInterface $command): void;
}