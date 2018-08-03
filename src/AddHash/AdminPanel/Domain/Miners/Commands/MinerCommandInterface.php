<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Commands;

interface MinerCommandInterface
{
    public function getConfig();

    public function getSummary();

    public function getPools();

    public function getState();
}