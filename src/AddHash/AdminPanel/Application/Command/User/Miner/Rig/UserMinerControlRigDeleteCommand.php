<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Rig;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigDeleteCommandInterface;

class UserMinerControlRigDeleteCommand implements UserMinerControlRigDeleteCommandInterface
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $rigId;

    public function __construct($rigId)
    {
        $this->rigId = $rigId;
    }

    public function getRigId(): int
    {
        return $this->rigId;
    }
}