<?php

namespace App\AddHash\AdminPanel\Domain\User;

use Doctrine\Common\Collections\ArrayCollection;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

class User
{
    private $id;

    private $firstName = '';

    private $lastName = '';

    private $backupEmail = '';

    private $phone = '';

    private $updatedAt;

    private $order;

    private $wallet;

    private $vote;

    private $orderMain;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->updatedAt = new \DateTime();

        $this->order = new ArrayCollection();
        $this->wallet = new ArrayCollection();
        $this->vote = new ArrayCollection();
        $this->orderMain = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBackupEmail(): string
    {
        return $this->backupEmail;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getVote()
    {
        return $this->vote;
    }

    public function getUserWallets()
    {
        return $this->wallet;
    }

    public function getOrderMiner()
    {
        return $this->orderMain;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function setBackupEmail(?Email $email)
    {
        $this->backupEmail = $email;
    }

    public function setPhoneNumber(?Phone $phoneNumber)
    {
        $this->phone = $phoneNumber;
    }
}