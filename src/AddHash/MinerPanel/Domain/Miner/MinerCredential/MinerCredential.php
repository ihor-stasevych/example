<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCredential;

class MinerCredential
{
    const DEFAULT_PORT = 4028;


    private $id;

    private $ip;

    private $port;

    public function __construct(string $ip, int $port = null, int $id = null)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->setPort($port);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function setPort(?int $port): void
    {
        $this->port = $port ?? static::DEFAULT_PORT;
    }
}