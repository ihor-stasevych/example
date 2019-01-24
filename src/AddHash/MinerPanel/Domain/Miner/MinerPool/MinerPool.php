<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerPool;

use App\AddHash\MinerPanel\Domain\Miner\Miner;

class MinerPool
{
    private $id;

    private $worker;

    private $url;

    private $password;

    private $miner;

    public function __construct(
        string $worker,
        string $url,
        string $password,
        Miner $miner,
        $id = null
    )
    {
        $this->id = $id;
        $this->worker = $worker;
        $this->url = $url;
        $this->password = $password;
        $this->miner = $miner;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorker(): string
    {
        return $this->worker;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}