<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class WalletRepository extends AbstractRepository implements WalletRepositoryInterface
{
    public function getByIds(array $ids)
    {
        $user = $this->entityManager->getRepository($this->getEntityName());

        $res = $user->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery();

        return $res->getArrayResult();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return Wallet::class;
    }
}