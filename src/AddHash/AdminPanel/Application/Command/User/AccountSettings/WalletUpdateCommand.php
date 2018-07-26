<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use App\AddHash\AdminPanel\Domain\User\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\WalletUpdateCommandInterface;

class WalletUpdateCommand implements WalletUpdateCommandInterface
{
    /**
     * @var array
     * @Assert\Type(type="array")
     * @Assert\NotNull()
     * @Assert\Expression(expression="this.isValidValueArray()")
     */
    private $wallets;

    private $user;

	public function __construct($wallets, User $user)
	{
		$this->wallets = $wallets;
		$this->user = $user;
	}

	public function getWallets(): array
    {
        return $this->wallets;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isValidValueArray()
    {
        $result = false;

        if ($this->wallets) {
            $result = true;

            foreach ($this->wallets as $walletNames) {
                if (!is_array($walletNames)) {
                    $result = false;
                    break;
                }

                if ($walletNames) {
                    foreach ($walletNames as $name) {
                        if (!is_string($name) || !$name) {
                            $result = false;
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }
}