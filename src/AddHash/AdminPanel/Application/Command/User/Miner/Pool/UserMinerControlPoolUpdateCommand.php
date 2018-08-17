<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolUpdateCommandInterface;

class UserMinerControlPoolUpdateCommand implements UserMinerControlPoolUpdateCommandInterface
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $minerId;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $poolId;

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

    public function __construct($minerId, $poolId, $url, $user, $password)
    {
        $this->minerId = $minerId;
        $this->poolId = $poolId;
        $this->user = $user;
        $this->url = $url;
        $this->password = $password;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getPoolId(): int
    {
        return $this->poolId;
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