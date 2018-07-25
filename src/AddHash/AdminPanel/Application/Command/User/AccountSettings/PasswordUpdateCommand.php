<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
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

	public function __construct($currentPassword, $newPassword, $confirmNewPassword)
	{
		$this->currentPassword = $currentPassword;
		$this->newPassword = $newPassword;
		$this->confirmNewPassword = $confirmNewPassword;
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

    public function comparePasswords()
    {
        return $this->newPassword == $this->confirmNewPassword;
    }
}