<?php

namespace App\AddHash\MinerPanel\Application\Command\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigGetCommandInterface;

final class RigGetCommand implements RigGetCommandInterface
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}