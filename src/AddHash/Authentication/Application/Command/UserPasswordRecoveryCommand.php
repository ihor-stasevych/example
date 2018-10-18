<?php

namespace App\AddHash\Authentication\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryCommandInterface;

final class UserPasswordRecoveryCommand implements UserPasswordRecoveryCommandInterface
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

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Expression(expression="this.comparePasswords()")
     */
    private $confirmPassword;

    public function __construct($hash, $password, $confirmPassword)
    {
        $this->hash = $hash;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function comparePasswords(): bool
    {
        return $this->password == $this->confirmPassword;
    }
}