<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Commands;

class MinerCommand extends AbstractMinerCommand
{
    public function getConfig()
    {
        return $this->minerConnection->request('config');
    }

    public function getSummary()
    {
        return $this->minerConnection->request('summary');
    }

    public function getPools()
    {
        return $this->minerConnection->request('pools');
    }

    public function getState()
    {
        return $this->minerConnection->request('stats');
    }

    public function getDevs()
    {
        return $this->minerConnection->request('devs');
    }

    public function getVersion()
    {
        return $this->minerConnection->request('version');
    }
}