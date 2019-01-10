<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerGetCommandInterface;

final class MinerGetCommand implements MinerGetCommandInterface
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