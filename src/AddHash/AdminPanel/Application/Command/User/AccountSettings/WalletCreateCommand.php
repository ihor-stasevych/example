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

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private $typeId;

	public function __construct($value, $typeId)
	{
		$this->value = $value;
        $this->typeId = $typeId;
	}

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }
}