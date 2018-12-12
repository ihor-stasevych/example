<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerPools;

use App\AddHash\MinerPanel\Domain\Miner\MinerPools\Command\MinerPoolsGetCommandInterface;

final class MinerPoolsGetCommand implements MinerPoolsGetCommandInterface
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}