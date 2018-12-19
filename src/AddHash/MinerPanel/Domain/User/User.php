<?php

namespace App\AddHash\MinerPanel\Domain\User;

use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\MinerPanel\Domain\Package\Model\Package;

class User
{
    private $id;

    private $miners;

    private $rigs;

    private $package;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->miners = new ArrayCollection();
        $this->rigs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPackage(Package $package): void
    {
        $this->package = $package;
    }
}