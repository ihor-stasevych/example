<?php

namespace App\AddHash\AdminPanel\Domain\Miners\Extender;

interface MinerInterface
{
    public function request(string $cmd);
}