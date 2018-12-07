<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand;

use App\AddHash\MinerPanel\Domain\Miner\Parsers\ParserInterface;
use App\AddHash\MinerPanel\Domain\Miner\Extender\MinerSocketInterface;
use App\AddHash\MinerPanel\Domain\Miner\ApiCommand\AbstractMinerApiCommandInterface;

abstract class AbstractMinerApiCommand implements AbstractMinerApiCommandInterface
{
    protected $minerConnection;

    protected $parser;

    public function __construct(MinerSocketInterface $minerConnection, ParserInterface $parser)
    {
        $this->minerConnection = $minerConnection;
        $this->parser = $parser;
    }

    public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }

    abstract public function getConfig();

    abstract public function getSummary();

    abstract public function getPools();

    abstract public function getState();

    abstract function addPool(string $url, string $user, string $password);

    abstract function removePool(int $id);

    abstract function disablePool(int $id);

    abstract function enablePool(int $id);

    abstract function restart();
}