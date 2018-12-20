<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Command;

interface RigListCommandInterface
{
    public function getPage(): ?int;
}