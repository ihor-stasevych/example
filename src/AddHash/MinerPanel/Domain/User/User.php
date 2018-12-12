<?php

namespace App\AddHash\MinerPanel\Domain\User;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Package\Model\Package;

class User
{
    private $id;

    private $miner;

    private $package;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPackage(Package $package)
    {
    	$this->package = $package;
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