<?php

namespace App\AddHash\AdminPanel\Domain\User;

use App\AddHash\AdminPanel\Domain\Wallet\Wallet;

class UserWallet
{
	/**
     * @var integer
     */
	private $id = null;

	/**
     * @var string
     */
	private $value;

	private $user;

	private $wallet;

	public function __construct(string $value, int $id = null)
	{
		$this->setId($id);
		$this->setValue($value);
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getWallet(): ?string
    {
        $walletName = null;

        if (null !== $this->wallet) {
            $walletName = $this->wallet->getName();
        }

        return $walletName;
    }

    private function setId($id = null)
    {
        if (null != $id) {
            $this->id = $id;
        }
    }

    public function setValue(string $value)
    {
        $this->value = $value;
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