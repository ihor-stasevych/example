<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\IpAddress;

use JJG\Ping;
use App\AddHash\MinerPanel\Domain\IpAddress\Command\IpAddressCheckCommandInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;

final class IpAddressCheckService implements IpAddressCheckServiceInterface
{
    private const PING_METHOD = 'fsockopen';

    /**
     * @param IpAddressCheckCommandInterface $command
     * @throws IpAddressCheckIpAddressUnavailableException
     */
    public function execute(IpAddressCheckCommandInterface $command): void
    {
        $ping = new Ping($command->getIp());

        $latency = $ping->ping(self::PING_METHOD);

        if (false === $latency) {
            throw new IpAddressCheckIpAddressUnavailableException(['ip' => ['Ip address unavailable']]);
        }
    }
}