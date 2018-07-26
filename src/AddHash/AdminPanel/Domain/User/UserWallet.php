<?php

namespace App\AddHash\AdminPanel\Domain\User;

class UserWallet
{
	/**
     * @var integer
     */
	private $id = null;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
	private $walletId;

	/**
     * @var string
     */
	private $name;

	public function __construct(int $id = null, int $userId, int $walletId, string $name)
	{
		$this->setId($id);
		$this->setUserId($userId);
		$this->setWalletId($walletId);
		$this->setName($name);
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getWalletId(): int
    {
        return $this->walletId;
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

    private function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    private function setWalletId(int $walletId)
    {
        $this->walletId = $walletId;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }
}