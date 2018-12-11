<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Command;

interface MinerUpdateCommandInterface
{
    public function getId(): int;

    public function getTitle(): string;

    public function getIp(): string;

    public function getPort(): ?int;

    public function getTypeId(): int;

    public function getAlgorithmId(): int;
}