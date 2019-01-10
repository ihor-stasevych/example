<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Services;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigUpdateCommandInterface;

interface RigUpdateServiceInterface
{
    public function execute(RigUpdateCommandInterface $command): array;
}