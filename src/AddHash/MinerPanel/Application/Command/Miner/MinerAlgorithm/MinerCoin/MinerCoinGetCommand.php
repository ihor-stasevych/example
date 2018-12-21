<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinGetCommandInterface;

final class MinerCoinGetCommand implements MinerCoinGetCommandInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}