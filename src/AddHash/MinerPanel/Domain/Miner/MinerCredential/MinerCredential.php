<?php

namespace App\AddHash\MinerPanel\Domain\Miner\MinerCredential;

class MinerCredential
{
    const DEFAULT_PORT = 4028;


    private $id;

    private $ip;

    private $port;

    private $portSsh;

    private $loginSsh;

    private $passwordSsh;

    public function __construct(string $ip, int $port = null, int $id = null)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->setPort($port);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
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

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function setPort(?int $port): void
    {
        $this->port = $port ?? static::DEFAULT_PORT;
    }

    public function setPortSsh(int $portSsh): void
    {
        $this->portSsh = $portSsh;
    }

    public function setLoginSsh(string $loginSsh): void
    {
        $this->loginSsh = $loginSsh;
    }

    public function setPasswordSsh(string $passwordSsh): void
    {
        $this->passwordSsh = $passwordSsh;
    }
}