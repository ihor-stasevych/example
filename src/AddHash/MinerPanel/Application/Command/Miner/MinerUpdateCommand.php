<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerUpdateCommandInterface;

final class MinerUpdateCommand implements MinerUpdateCommandInterface
{
    private $id;

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
     * @Assert\Regex("/^\d+$/")
     */
    private $port;

    /**
     * @Assert\Type("numeric")
     * @Assert\Regex("/^\d+$/")
     */
    private $portSsh;

    /**
     * @Assert\Expression(expression="this.requiredLoginSsh()")
     */
    private $loginSsh;

    /**
     * @Assert\Expression(expression="this.requiredPasswordSsh()")
     */
    private $passwordSsh;

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

    /**
     * @Assert\Regex("/^\d+$/")
     */
    private $rigId;

    public function __construct($id, $title, $ip, $port, $portSsh, $loginSsh, $passwordSsh, $typeId, $algorithmId, $rigId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->ip = $ip;
        $this->port = $port;
        $this->portSsh = $portSsh;
        $this->loginSsh = $loginSsh;
        $this->passwordSsh = $passwordSsh;
        $this->typeId = $typeId;
        $this->algorithmId = $algorithmId;
        $this->rigId = $rigId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPortSsh(): ?int
    {
        return $this->portSsh;
    }

    public function getLoginSsh(): ?string
    {
        return $this->loginSsh;
    }

    public function getPasswordSsh(): ?string
    {
        return $this->passwordSsh;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getAlgorithmId(): int
    {
        return $this->algorithmId;
    }

    public function getRigId(): ?int
    {
        return $this->rigId;
    }

    public function requiredLoginSsh(): bool
    {
        $check = true;

        if (true === empty($this->loginSsh)) {
            if (false === empty($this->portSsh) || false === empty($this->passwordSsh)) {
                $check = false;
            }
        }

        return $check;
    }

    public function requiredPasswordSsh(): bool
    {
        $check = true;

        if (true === empty($this->passwordSsh)) {
            if (false === empty($this->portSsh) || false === empty($this->loginSsh)) {
                $check = false;
            }
        }

        return $check;
    }
}