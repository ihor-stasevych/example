<?php

namespace App\AddHash\MinerPanel\Domain\Rig;

use Doctrine\ORM\PersistentCollection;
use App\AddHash\MinerPanel\Domain\User\User;
use App\AddHash\MinerPanel\Domain\Miner\Miner;
use Doctrine\Common\Collections\ArrayCollection;

class Rig
{
    const MAX_PER_PAGE = 10;


    private $id;

    private $title;

    private $worker;

    private $url;

    private $password;

    private $user;

    private $miners;

    public function __construct(
        string $title,
        string $worker,
        string $url,
        ?string $password,
        User $user,
        int $id = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->worker = $worker;
        $this->url = $url;
        $this->setPassword($password);
        $this->user = $user;
        $this->miners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
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

    public function getMiners()
    {
        /** @var PersistentCollection $miners */
        $miners = $this->miners;

        return $miners;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setWorker(string $worker): void
    {
        $this->worker = $worker;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password ?? '';
    }

    public function setMiner(Miner $miner): void
    {
        if (false === $this->miners->contains($miner)) {
            $this->miners->add($miner);
            $miner->setRig($this);
        }
    }

    public function removeMiner(Miner $miner): void
    {
        if (true === $this->miners->contains($miner)) {
            $this->miners->removeElement($miner);
            $miner->removeRig($this);
        }
    }
}