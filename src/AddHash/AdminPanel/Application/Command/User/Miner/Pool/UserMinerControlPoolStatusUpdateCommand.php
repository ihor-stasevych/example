<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolStatusUpdateCommandInterface;

class UserMinerControlPoolStatusUpdateCommand implements UserMinerControlPoolStatusUpdateCommandInterface
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

    private $status;

    public function __construct($minerId, $poolId, $status)
    {
        $this->minerId = $minerId;
        $this->poolId = $poolId;
        $this->status = $status;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getPoolId(): int
    {
        return $this->poolId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}