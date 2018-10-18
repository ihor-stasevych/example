<?php

namespace App\AddHash\Authentication\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryHashCommandInterface;

final class UserPasswordRecoveryHashCommand implements UserPasswordRecoveryHashCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Your hash must be at least {{ limit }} characters long"
     * )
     */
    private $hash;

    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}