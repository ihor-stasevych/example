<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolGetCommandInterface;

final class MinerPoolGetCommand implements MinerPoolGetCommandInterface
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