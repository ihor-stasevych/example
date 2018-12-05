<?php

namespace App\AddHash\MinerPanel\Domain\User\Model;

use App\AddHash\MinerPanel\Domain\Miner\Model\Miner;

class User
{
    private $id;

    private $miner;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMiner(): ?Miner
    {
        return $this->miner;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}