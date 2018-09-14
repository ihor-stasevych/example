<?php

namespace App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig;

interface UserMinerControlRigUpdateCommandInterface
{
    public function getRigId(): int;

    public function getMinersStockId(): array;

    public function getName(): string;

    public function getWorker(): string;

    public function getUrl(): string;

    public function getPassword(): ?string;
}