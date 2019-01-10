<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Command\MinerDeleteCommandInterface;

final class MinerDeleteCommand implements MinerDeleteCommandInterface
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