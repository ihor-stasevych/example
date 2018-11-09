<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\Validation\CustomValidator\ValidatorIntegerTrait;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletCreateCommandInterface;

final class WalletCreateCommand implements WalletCreateCommandInterface
{
    use ValidatorIntegerTrait;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Your value must be at least {{ limit }} characters long",
     *      maxMessage = "Your value cannot be longer than {{ limit }} characters"
     * )
     */
    private $value;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Expression(expression="this.isInteger(value)")
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