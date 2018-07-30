<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;

class WalletCreateCommand implements WalletCreateCommandInterface
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $walletId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $value;

	public function __construct($walletId, $value)
	{
		$this->walletId = $walletId;
		$this->value = $value;
	}

	public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}