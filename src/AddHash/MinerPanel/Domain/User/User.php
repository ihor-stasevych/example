<?php

namespace App\AddHash\MinerPanel\Domain\User;

use App\AddHash\MinerPanel\Domain\Package\Model\Package;

class User
{
    private $id;

    private $package;

    public function __construct(int $id)
    {
        $this->id = $id;
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