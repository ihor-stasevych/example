<?php
namespace App\AddHash\AdminPanel\Infrastructure\Repository\Wallet;

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
        $wallet = $this->entityManager->getRepository($this->getEntityName());

        $res = $wallet->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

    /**
     * @param string $value
     * @return Wallet|null
     * @throws NonUniqueResultException
     */
    public function getByValue(string $value): ?Wallet
    {
        $wallet = $this->entityManager->getRepository($this->getEntityName());

        $res = $wallet->createQueryBuilder('w')
            ->select('w')
            ->andWhere('w.value = :value')
            ->setParameter('value', $value)
            ->getQuery();

        return $res->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param Wallet $wallet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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