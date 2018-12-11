<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerSummary;

use App\AddHash\MinerPanel\Domain\Miner\MinerSummary\Command\MinerSummaryGetCommandInterface;

final class MinerSummaryGetCommand implements MinerSummaryGetCommandInterface
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}