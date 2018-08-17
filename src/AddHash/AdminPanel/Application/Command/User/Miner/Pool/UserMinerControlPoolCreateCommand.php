<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

class UserMinerControlPoolCreateCommand implements UserMinerControlPoolCreateCommandInterface
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $minerId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $password;

    public function __construct($minerId, $url, $user, $password)
    {
        $this->minerId = $minerId;
        $this->url = $url;
        $this->user = $user;
        $this->password = $password;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}