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

    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    public function getIp(): string
    {
        return $this->ip;
    }
}