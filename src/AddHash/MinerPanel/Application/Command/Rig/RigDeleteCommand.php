<?php

namespace App\AddHash\MinerPanel\Application\Command\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Command\RigDeleteCommandInterface;

final class RigDeleteCommand implements RigDeleteCommandInterface
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