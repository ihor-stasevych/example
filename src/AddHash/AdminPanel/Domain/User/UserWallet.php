<?php

namespace App\AddHash\AdminPanel\Domain\User;

class UserWallet
{
	/**
     * @var integer
     */
	private $id = null;

	/**
     * @var string
     */
	private $name;

	private $user;

	private $wallet;

	public function __construct(int $id = null, int $userId, int $walletId, string $name)
	{
		$this->setId($id);
		//$this->setUserId($userId);
		//$this->setWalletId($walletId);
		$this->setName($name);
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setId($id = null)
    {
        if (null != $id) {
            $this->id = $id;
        }
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}