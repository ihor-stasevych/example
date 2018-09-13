<?php

namespace App\AddHash\AdminPanel\Domain\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\StrategyInterface;

interface UserMinerControlPoolContextInterface
{
    public function addStrategy(StrategyInterface $strategy);

    public function handle($data);
}