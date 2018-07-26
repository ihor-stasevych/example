<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;

class WalletUpdateCommand implements WalletUpdateCommandInterface
{
    /**
     * @var array
     * @Assert\Type(type="array")
     * @Assert\NotNull()
     */
    private $wallets;

    private $user;

	public function __construct($wallets, User $user)
	{
		$this->wallets = $wallets;
		$this->user = $user;
	}

	public function getWallets(): array
    {
        return $this->wallets;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}