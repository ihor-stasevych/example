<?php

namespace App\AddHash\MinerPanel\Domain\Miner;

use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerType;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;

class Miner
{
    const DEFAULT_PORT = 4028;

    const MAX_PER_PAGE = 10;


    private $id;

    private $title;

    private $ip;

    private $port;

    private $hashRate;

    private $type;

    private $algorithm;

    private $user;

    public function __construct(
        string $title,
        string $ip,
        ?int $port,
        float $hashRate,
        MinerType $type,
        MinerAlgorithm $algorithm,
        User $user,
        int $id = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->ip = $ip;
        $this->port = $port ?? self::DEFAULT_PORT;
        $this->hashRate = $hashRate;
        $this->type = $type;
        $this->algorithm = $algorithm;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getHashRate(): float
    {
        return $this->hashRate;
    }

    public function getType(): MinerType
    {
        return $this->type;
    }

    public function getAlgorithm(): MinerAlgorithm
    {
        return $this->algorithm;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function setPort(?int $port): void
    {
        $this->port = $port ?? self::DEFAULT_PORT;
    }

    public function setHashRate(float $hashRate): void
    {
        $this->hashRate = $hashRate;
    }

    public function setType(MinerType $type): void
    {
        $this->type = $type;
    }

    public function setAlgorithm(MinerAlgorithm $algorithm): void
    {
        $this->algorithm = $algorithm;
    }
}