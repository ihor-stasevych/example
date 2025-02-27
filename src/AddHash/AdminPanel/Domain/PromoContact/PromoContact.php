<?php

namespace App\AddHash\AdminPanel\Domain\PromoContact;

class PromoContact
{
    const MR = 1;

    const MRS = 0;

    const DEFAULT_GENDER = self::MR;


    private $id;

    private $email;

    private $gender;

    private $name;

    private $message;

    private $createdAt;

    public function __construct(string $email, string $name, string $message, int $id = null)
    {
        $this->email = $email;
        $this->gender = static::DEFAULT_GENDER;
        $this->name = $name;
        $this->message = $message;
        $this->createdAt = new \DateTime();
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setGenderMr()
    {
        $this->gender = static::MR;
    }

    public function setGenderMrs()
    {
        $this->gender = static::MRS;
    }
}