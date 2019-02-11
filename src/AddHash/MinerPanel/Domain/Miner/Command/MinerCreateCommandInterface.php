<?php

namespace App\AddHash\MinerPanel\Domain\Miner\Command;

interface MinerCreateCommandInterface
{
    public function getTitle(): string;

    public function getIp(): string;

    public function getPort(): ?int;

    public function getPortSsh(): ?int;

    public function getLoginSsh(): ?string;

    public function getPasswordSsh(): ?string;

    public function getTypeId(): int;

    public function getAlgorithmId(): int;

    public function getRigId(): ?int;
}