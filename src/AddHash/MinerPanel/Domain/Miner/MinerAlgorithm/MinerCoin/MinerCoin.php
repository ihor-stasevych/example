<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin;

use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;

class MinerCoin
{
    private $id;

    private $name;

    private $tag;

    private $difficulty;

    private $reward;

    private $updatedAt;

    private $algorithm;

    public function __construct(string $name, string $tag, string $difficulty, string $reward, MinerAlgorithm $algorithm, int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tag = $tag;
        $this->difficulty = $difficulty;
        $this->reward = $reward;
        $this->updatedAt = new \DateTime();
        $this->algorithm = $algorithm;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    public function getReward(): float
    {
        return $this->reward;
    }

    public function infoAlgorithm(): MinerAlgorithm
    {
        return $this->algorithm;
    }

    public function setDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function setReward(string $reward): void
    {
        $this->reward = $reward;
    }

    public function setUpdateAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}