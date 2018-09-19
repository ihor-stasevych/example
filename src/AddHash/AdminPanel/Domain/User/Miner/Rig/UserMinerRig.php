<?php

namespace App\AddHash\AdminPanel\Domain\User\Miner\Rig;

use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use Doctrine\Common\Collections\ArrayCollection;

class UserMinerRig
{
    private $id;

    private $name;

    private $worker;

    private $url;

    private $password;

    private $user;

    private $minersStock;

    public function __construct(
        string $name,
        string $worker,
        string $url,
        ?string $password,
        ?int $id
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->worker = $worker;
        $this->url = $url;
        $this->setPassword($password);
        $this->minersStock = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWorker(): string
    {
        return $this->worker;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMinersStock()
    {
        return $this->minersStock;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setWorker(string $worker)
    {
        $this->worker = $worker;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function setPassword(?string $password)
    {
        $this->password = (null !== $password) ? $password : '';
    }

    public function setMinerStock(MinerStock $minerStock)
    {
        $this->minersStock = $minerStock;
    }
}