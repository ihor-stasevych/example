<?php

namespace App\AddHash\MinerPanel\Domain\IpAddress\Command;

interface IpAddressCheckCommandInterface
{
    public function getIp(): string;

    public function getPort(): ?int;
}