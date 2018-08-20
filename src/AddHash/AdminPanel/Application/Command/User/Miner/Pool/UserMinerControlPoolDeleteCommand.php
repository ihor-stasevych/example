<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolDeleteCommandInterface;

class UserMinerControlPoolDeleteCommand implements UserMinerControlPoolDeleteCommandInterface
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

    public function __construct($minerId, $poolId)
    {
        $this->minerId = $minerId;
        $this->poolId = $poolId;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getPoolId(): int
    {
        return $this->poolId;
    }
}