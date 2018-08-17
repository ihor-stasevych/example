<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Commands;

use App\AddHash\AdminPanel\Domain\Miners\Parsers\ParserInterface;
use App\AddHash\AdminPanel\Domain\Miners\Extender\MinerInterface;
use App\AddHash\AdminPanel\Domain\Miners\Commands\MinerCommandInterface;

abstract class AbstractMinerCommand implements MinerCommandInterface
{
    protected $minerConnection;

    protected $parser;

    public function __construct(MinerInterface $minerConnection, ParserInterface $parser)
    {
        $this->minerConnection = $minerConnection;
        $this->parser = $parser;
    }

    public function setParser(ParserInterface $parser)
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
}