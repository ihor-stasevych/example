<?php

namespace App\AddHash\MinerPanel\Domain\IpAddress\Services;

use App\AddHash\MinerPanel\Domain\IpAddress\Command\IpAddressCheckCommandInterface;

interface IpAddressCheckServiceInterface
{
    public function execute(IpAddressCheckCommandInterface $command): void;
}