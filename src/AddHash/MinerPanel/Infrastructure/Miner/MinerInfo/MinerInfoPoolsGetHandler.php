<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\MinerInfo;

use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\Parser;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\MinerPoolsParser;
use App\AddHash\MinerPanel\Domain\Miner\ApiCommand\AbstractMinerApiCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerInfo\MinerInfoPoolsGetHandlerInterface;

final class MinerInfoPoolsGetHandler extends AbstractMinerInfoHandler implements MinerInfoPoolsGetHandlerInterface
{
    protected const MINER_INFO_KEY = 'miner_pools_';

    protected const EXPIRATION = 180;


    protected function getParser(): Parser
    {
        return new MinerPoolsParser();
    }

    protected function executeCommand(AbstractMinerApiCommandInterface $command): array
    {
        return $command->getPools();
    }
}