<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\StrategyInterface;
use App\AddHash\AdminPanel\Domain\User\Miner\Pool\UserMinerControlPoolContextInterface;

class UserMinerControlPoolContext implements UserMinerControlPoolContextInterface
{
    private $strategies = [];

    public function addStrategy(StrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    public function handle($data)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canProcess($data)) {
                return $strategy->process($data);
            }
        }

        return $data;
    }
}