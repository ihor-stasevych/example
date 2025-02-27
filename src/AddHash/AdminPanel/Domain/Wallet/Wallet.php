<?php

namespace App\AddHash\AdminPanel\Domain\Wallet;

class Wallet
{
	private $id;

	private $value;

	private $type;

	public function __construct(string $value, int $id = null)
	{
        $this->id = $id;
        $this->value = $value;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): WalletType
    {
        return $this->type;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function setType(WalletType $type)
    {
        $this->type = $type;
    }
}