<?php

namespace App\AddHash\MinerPanel\Domain\User;

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

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}