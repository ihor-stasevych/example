<?php

namespace App\AddHash\AdminPanel\Infrastructure\Miners\Commands;

class MinerCommand extends AbstractMinerCommand
{
    public function request($cmd)
    {
        return $this->parser->normalizeData($this->minerConnection->request($cmd));
    }

    public function getConfig()
    {
        return $this->request('config');
    }

    public function getSummary()
    {
        return $this->request('summary');
    }

    public function getPools()
    {
        return $this->request('pools');
    }

    public function getState()
    {
        return $this->request('stats');
    }

    public function getVersion()
    {
        return $this->request('version');
    }

    public function addPool(string $url, string $user, string $password)
    {
        return $this->request('addpool|' . $url . ',' . $user . ',' . $password);
    }

    public function removePool(int $id)
    {
        return $this->request('removepool|' . $id);
    }

    public function disablePool(int $id)
    {
        return $this->request('disablepool|' . $id);
    }

    public function enablePool(int $id)
    {
        return $this->request('enablepool|' . $id);
    }

    public function setPoolPriority(string $ids)
    {
        return $this->request('poolpriority|' . $ids);
    }
}