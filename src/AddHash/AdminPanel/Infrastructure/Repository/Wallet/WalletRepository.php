<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class WalletRepository extends AbstractRepository implements WalletRepositoryInterface
{
    /**
     * @return string
     */
    protected function getEntityName()
    {
        return Wallet::class;
    }
}