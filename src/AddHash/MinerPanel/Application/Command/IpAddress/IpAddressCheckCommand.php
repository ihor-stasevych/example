<?php

namespace App\AddHash\MinerPanel\Application\Command\IpAddress;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\IpAddress\Command\IpAddressCheckCommandInterface;

final class IpAddressCheckCommand implements IpAddressCheckCommandInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Ip
     */
    private $ip;

    /**
     * @Assert\Regex("/^\d+$/")
     */
    private $port;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }
}