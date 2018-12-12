<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Services;

use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Command\MinerSummaryGetCommandInterface;

interface MinerSummaryGetServiceInterface
{
    public function execute(MinerSummaryGetCommandInterface $command): array;
}