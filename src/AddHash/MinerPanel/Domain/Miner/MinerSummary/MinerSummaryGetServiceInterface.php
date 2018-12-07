<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerSummary;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerSummary\MinerSummaryGetCommandInterface;

interface MinerSummaryGetServiceInterface
{
    public function execute(MinerSummaryGetCommandInterface $command): array;
}