<?php

namespace App\AddHash\AdminPanel\Domain\User;

use App\AddHash\AdminPanel\Domain\Wallet\Wallet;

class UserWallet
{
	private $id;

	private $user;

    /**
     * @var Wallet
     */
	private $wallet;

	public function __construct(int $id = null)
	{
        $this->id = $id;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet()
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}