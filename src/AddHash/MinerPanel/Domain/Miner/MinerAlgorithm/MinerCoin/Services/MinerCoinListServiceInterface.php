<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Services;

use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinListCommandInterface;

interface MinerCoinListServiceInterface
{
    public function execute(MinerCoinListCommandInterface $command): ResponseListCollection;
}