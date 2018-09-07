<?php

namespace App\AddHash\AdminPanel\Application\Command\PromoContact;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\PromoContact\Command\PromoContactCreateCommandInterface;

class PromoContactCreateCommand implements PromoContactCreateCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $gender;

    public function __construct($email, $name, $message, $gender)
    {
        $this->email = $email;
        $this->name = $name;
        $this->message = $message;
        $this->gender = $gender;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}