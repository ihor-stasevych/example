<?php

namespace App\AddHash\MinerPanel\Domain\Miner;

use App\AddHash\MinerPanel\Domain\Miner\MinerConfig\MinerConfig;
use Doctrine\ORM\PersistentCollection;
use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\MinerPanel\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\MinerPanel\Domain\Miner\MinerType\MinerType;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerAlgorithm;
use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;

class Miner
{
    const MAX_PER_PAGE = 10;

    const STATUS_POOL_ON = 1;

    const STATUS_POOL_OFF = 0;

    const STATUS_POOL_DEFAULT = self::STATUS_POOL_OFF;


    private $id;

    private $title;

    private $hashRate;

    private $credential;

    private $type;

    private $algorithm;

    private $user;

    private $rigs;

    private $statusPool;

    private $pools;

    private $config;

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
        $this->statusPool = static::STATUS_POOL_DEFAULT;
        $this->algorithm = $algorithm;
        $this->user = $user;
        $this->rigs = new ArrayCollection();
        $this->pools = new ArrayCollection();
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

    public function infoRigs(): PersistentCollection
    {
        /** @var PersistentCollection $rigs */
        $rigs = $this->rigs;

        return $rigs;
    }

    public function getPools(): PersistentCollection
    {
        /** @var PersistentCollection $pools */
        $pools = $this->pools;

        return $pools;
    }

    public function getConfig(): MinerConfig
    {
        return $this->config;
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

    public function setStatusPoolOn(): void
    {
        $this->statusPool = static::STATUS_POOL_ON;
    }

    public function setStatusPoolOff(): void
    {
        $this->statusPool = static::STATUS_POOL_OFF;
    }

    public function setRig(Rig $rig): void
    {
        if (false === $this->rigs->contains($rig)) {
            $this->rigs->add($rig);
            $rig->setMiner($this);
        }
    }

    public function removeRig(Rig $rig): void
    {
        if (true === $this->rigs->contains($rig)) {
            $this->rigs->removeElement($rig);
            $rig->removeMiner($this);
        }
    }
}