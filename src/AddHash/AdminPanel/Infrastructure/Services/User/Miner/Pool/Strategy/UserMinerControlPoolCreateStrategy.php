<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool\Strategy;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\Strategy\StrategyInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;

class UserMinerControlPoolCreateStrategy implements StrategyInterface
{
    const STRATEGY_FLAG = 'create';

    private $logger;

    private $allowedUrlRepository;

    public function __construct(LoggerInterface $logger, MinerAllowedUrlRepositoryInterface $allowedUrlRepository)
    {
        $this->logger = $logger;
        $this->allowedUrlRepository = $allowedUrlRepository;
    }

    public function canProcess($data)
    {
        return static::STRATEGY_FLAG == $data;
    }

    public function process($data)
    {
        dd($data);
    }
}