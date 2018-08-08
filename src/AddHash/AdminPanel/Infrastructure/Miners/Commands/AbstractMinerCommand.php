<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Commands;

use App\AddHash\AdminPanel\Domain\Miners\Extender\MinerInterface;
use App\AddHash\AdminPanel\Domain\Miners\Commands\MinerCommandInterface;

abstract class AbstractMinerCommand implements MinerCommandInterface
{
    protected $minerConnection;

    public function __construct(MinerInterface $minerConnection)
    {
        $this->minerConnection = $minerConnection;
    }

    abstract public function getConfig();

    abstract public function getSummary();

    abstract public function getPools();

    abstract public function getState();
}