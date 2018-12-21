<?php

namespace App\AddHash\MinerPanel\Domain\Rig\Command;

interface RigUpdateCommandInterface
{
    public function getId(): int;

    public function getTitle(): string;

    public function getWorker(): string;

    public function getUrl(): string;

    public function getPassword(): ?string;

    public function getMinersId(): ?array;
}