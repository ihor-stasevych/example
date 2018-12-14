<?php

namespace App\AddHash\MinerPanel\Domain\Rig;

use Doctrine\ORM\PersistentCollection;
use App\AddHash\AdminPanel\Domain\User\User;
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
        string $password,
        User $user,
        int $id = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->worker = $worker;
        $this->url = $url;
        $this->password = $password;
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

    public function getMiners(): PersistentCollection
    {
        /** @var PersistentCollection $miners */
        $miners = $this->miners;

        return $miners;
    }
}