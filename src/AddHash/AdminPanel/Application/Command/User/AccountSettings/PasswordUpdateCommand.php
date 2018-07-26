<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\PasswordUpdateCommandInterface;

class PasswordUpdateCommand implements PasswordUpdateCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $currentPassword;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $newPassword;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Expression(expression="this.comparePasswords()")
     */
    private $confirmNewPassword;

    /**
     * @var User
     */
    private $user;

	public function __construct($currentPassword, $newPassword, $confirmNewPassword, User $user)
	{
		$this->currentPassword = $currentPassword;
		$this->newPassword = $newPassword;
		$this->confirmNewPassword = $confirmNewPassword;
		$this->user = $user;
	}

    public function getCurrentPassword(): string
    {
	    return $this->currentPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getConfirmNewPassword(): string
    {
        return $this->confirmNewPassword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function comparePasswords(): bool
    {
        return $this->newPassword == $this->confirmNewPassword;
    }
}