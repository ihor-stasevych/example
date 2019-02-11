<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\IpAddress;

use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;
use App\AddHash\MinerPanel\Domain\IpAddress\Command\IpAddressCheckCommandInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Services\IpAddressCheckServiceInterface;
use App\AddHash\MinerPanel\Domain\IpAddress\Exceptions\IpAddressCheckIpAddressUnavailableException;

final class IpAddressCheckService implements IpAddressCheckServiceInterface
{
    private const TIMEOUT = 10;

    /**
     * @param IpAddressCheckCommandInterface $command
     * @throws IpAddressCheckIpAddressUnavailableException
     */
    public function execute(IpAddressCheckCommandInterface $command): void
    {
        $port = $command->getPort() ?? MinerCredential::DEFAULT_PORT;

        $fp = @fsockopen($command->getIp(), $port, $errno, $errstr, self::TIMEOUT);

        if (false === $fp) {
            throw new IpAddressCheckIpAddressUnavailableException(['ip' => ['Ip address or port invalid']]);
        }

        fclose($fp);
    }
}