<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;

class WalletUpdateCommand implements WalletUpdateCommandInterface
{
    /**
     * @var array
     * @Assert\All({
     *   @Assert\Collection(
     *      fields = {
     *          "id" = @Assert\Required({@Assert\NotNull()}),
     *          "value" = @Assert\Required({@Assert\NotNull()})
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