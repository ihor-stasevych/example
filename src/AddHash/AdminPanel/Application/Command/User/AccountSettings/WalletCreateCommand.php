<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;

class WalletCreateCommand implements WalletCreateCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

    public function getValue(): string
    {
        return $this->value;
    }
}