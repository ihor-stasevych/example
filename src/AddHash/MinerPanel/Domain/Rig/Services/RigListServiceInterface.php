<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Services;

use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigListCommandInterface;

interface RigListServiceInterface
{
    public function execute(RigListCommandInterface $command): ResponseListCollection;
}