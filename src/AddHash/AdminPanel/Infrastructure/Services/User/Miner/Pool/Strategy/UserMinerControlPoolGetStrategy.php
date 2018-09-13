<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool\Strategy;

use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\StrategyInterface;

class UserMinerControlPoolGetStrategy implements StrategyInterface
{
    const STRATEGY_FLAG = 'get';

    public function canProcess($data)
    {
        return static::STRATEGY_FLAG == $data;
    }

    public function process($data)
    {
        dd($data);
    }
}