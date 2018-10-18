<?php

namespace App\AddHash\Authentication\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryRequestCommandInterface;

final class UserPasswordRecoveryRequestCommand implements UserPasswordRecoveryRequestCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }
}