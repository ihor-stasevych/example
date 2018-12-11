<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerInfo;

use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\Parser;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\MinerSummaryParser;
use App\AddHash\MinerPanel\Domain\Miner\ApiCommand\AbstractMinerApiCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoSummaryGetHandlerInterface;

final class MinerInfoSummaryGetHandler extends AbstractMinerInfoHandler implements MinerInfoSummaryGetHandlerInterface
{
    protected const MINER_INFO_KEY = 'miner_summary_';

    protected const EXPIRATION = 180;


    protected function getParser(): Parser
    {
        return new MinerSummaryParser();
    }

    protected function executeCommand(AbstractMinerApiCommandInterface $command): array
    {
        return $command->getSummary();
    }
}