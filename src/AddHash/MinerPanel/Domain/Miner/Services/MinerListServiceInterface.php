<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Services;

use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerListCommandInterface;

interface MinerListServiceInterface
{
    public function execute(MinerListCommandInterface $command): ResponseListCollection;
}