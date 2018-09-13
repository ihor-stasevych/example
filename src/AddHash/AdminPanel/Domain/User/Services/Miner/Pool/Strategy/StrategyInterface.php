<?php

namespace App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy;

interface StrategyInterface
{
    public function canProcess($data);

    public function process($data);
}