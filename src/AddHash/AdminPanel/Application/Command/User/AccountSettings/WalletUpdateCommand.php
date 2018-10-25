<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\Validation\CustomValidator\ValidatorIntegerTrait;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;

final class WalletUpdateCommand implements WalletUpdateCommandInterface
{
    use ValidatorIntegerTrait;

    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\All({
     *   @Assert\Collection(
     *      fields = {
     *          "id" = @Assert\Required({
     *              @Assert\NotBlank(),
     *              @Assert\Expression(expression="this.isInteger(value)")
     *          }),
     *          "typeId" = @Assert\Required({
     *              @Assert\NotBlank(),
     *              @Assert\Expression(expression="this.isInteger(value)")
     *          }),
     *          "value" = @Assert\Required({
     *              @Assert\NotBlank(),
     *              @Assert\Length(
     *                  min = 2,
     *                  max = 255,
     *                  minMessage = "Your value must be at least {{ limit }} characters long",
     *                  maxMessage = "Your value cannot be longer than {{ limit }} characters"
     *              )
     *          })
     *      }
     *   )
     * })
     */
    private $wallets;

	public function __construct($wallets)
	{
		$this->wallets = $wallets;
	}

	public function getWallets(): array
    {
        return $this->wallets;
    }
}