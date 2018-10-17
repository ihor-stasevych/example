<?php

namespace App\AddHash\Authentication\Domain\Model;

use App\AddHash\System\Lib\Uuid;

class UserPasswordRecovery
{
    private const DURATION_TIME = 7200;


    private $id;

    private $user;

    private $hash;

    private $requestedDate;

    private $expirationDate;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->setExpirationDate();
        $this->requestedDate = new \DateTime();
        $this->hash = Uuid::generate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function getRequestedDate(): \DateTime
    {
        return $this->requestedDate;
    }

    public function getExpirationDate(): \DateTime
    {
        return $this->expirationDate;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    private function setExpirationDate()
    {
        $dataTime = new \DateTime();
        $newDate = $dataTime->getTimestamp() + self::DURATION_TIME;

        $this->expirationDate = $dataTime->setTimestamp($newDate);
    }
}