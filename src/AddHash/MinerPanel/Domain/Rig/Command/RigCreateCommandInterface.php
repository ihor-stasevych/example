<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Command;

interface RigCreateCommandInterface
{
    public function getTitle(): string;

    public function getWorker(): string;

    public function getUrl(): string;

    public function getPassword(): ?string;

    public function getMinersId(): ?array;
}