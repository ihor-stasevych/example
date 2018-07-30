<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;

class WalletCreateCommand implements WalletCreateCommandInterface
{
    private $walletId;

    private $name;

	public function __construct($walletId, $name)
	{
		$this->walletId = $walletId;
		$this->name = $name;
	}

	public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}