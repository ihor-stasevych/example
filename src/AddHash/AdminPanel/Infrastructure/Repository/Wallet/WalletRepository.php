<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use App\AddHash\AdminPanel\Domain\Wallet\Wallet;
use App\AddHash\AdminPanel\Domain\Wallet\WalletRepositoryInterface;
use App\AddHash\System\GlobalContext\Repository\AbstractRepository;

class WalletRepository extends AbstractRepository implements WalletRepositoryInterface
{
    /**
     * @param int $id
     * @return Wallet|null
     * @throws NonUniqueResultException
     */
    public function getById(int $id): ?Wallet
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $value
     * @param int $typeId
     * @return Wallet|null
     * @throws NonUniqueResultException
     */
    public function getByValueAndType(string $value, int $typeId): ?Wallet
    {
        return $this->entityManager
            ->getRepository($this->getEntityName())
            ->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.value = :value')
            ->andWhere('w.type = :typeId')
            ->setParameter('value', $value)
            ->setParameter('typeId', $typeId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param Wallet $wallet
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Wallet $wallet)
    {
        $this->entityManager->persist($wallet);
        $this->entityManager->flush($wallet);
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return Wallet::class;
    }
}