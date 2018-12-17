<?php

namespace App\AddHash\MinerPanel\Domain\Miner;

use App\AddHash\MinerPanel\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerType;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;

class Miner
{
    const MAX_PER_PAGE = 10;


    private $id;

    private $title;

    private $hashRate;

    private $credential;

    private $type;

    private $algorithm;

    private $user;

    private $rigs;

    public function __construct(
        string $title,
        float $hashRate,
        MinerCredential $credential,
        MinerType $type,
        MinerAlgorithm $algorithm,
        User $user,
        int $id = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->credential = $credential;
        $this->hashRate = $hashRate;
        $this->type = $type;
        $this->algorithm = $algorithm;
        $this->user = $user;
        $this->rigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCredential(): MinerCredential
    {
        return $this->credential;
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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setCredential(MinerCredential $credential): void
    {
        $this->credential = $credential;
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