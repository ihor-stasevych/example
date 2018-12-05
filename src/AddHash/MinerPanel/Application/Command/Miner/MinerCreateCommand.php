<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerCreateCommandInterface;

final class MinerCreateCommand implements MinerCreateCommandInterface
{
    /**
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Ip
     */
    private $ip;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d+$/")
     */
    private $port;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d+$/")
     */
    private $typeId;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d+$/")
     */
    private $algorithmId;

    public function __construct($title, $ip, $port, $typeId, $algorithmId)
    {
        $this->title = $title;
        $this->ip = $ip;
        $this->port = $port;
        $this->typeId = $typeId;
        $this->algorithmId = $algorithmId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getAlgorithmId(): int
    {
        return $this->algorithmId;
    }
}