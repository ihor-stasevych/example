<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

use App\AddHash\AdminPanel\Domain\Wallet\WalletType;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;
use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;

class WalletTypeRepository extends AbstractRepository implements WalletTypeRepositoryInterface
{
    /**
     * @param int $id
     * @return WalletType|null
     */
    public function getById(int $id): ?WalletType
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->find($id);
    }

    /**
     * @param array $ids
     * @return array|null
     */
    public function getByIds(array $ids): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('wt')
            ->select('wt')
            ->andWhere('wt.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->findAll();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return WalletType::class;
    }
}