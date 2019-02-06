<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services;

interface MinerPoolStatusUpdateServiceInterface
{
    public function execute(): void;
}