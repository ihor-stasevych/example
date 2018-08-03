<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Commands;

use App\AddHash\AdminPanel\Domain\Miners\Extender\MinerSocketInterface;
use App\AddHash\AdminPanel\Domain\Miners\Commands\MinerCommandInterface;

class AbstractMinerCommand implements MinerCommandInterface
{
    protected $minerSocket;

    public function __construct(MinerSocketInterface $minerSocket)
    {
        $this->minerSocket = $minerSocket;
    }

    public function getConfig()
    {

    }

    public function getSummary()
    {

    }

    public function getPools()
    {

    }

    public function getState()
    {

    }
}